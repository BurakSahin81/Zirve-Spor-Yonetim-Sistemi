<?php
session_start();
require_once 'db.php';


if (!isset($_SESSION['uye_id'])) {
    header("Location: giris.php");
    exit;
}

$uye_id = $_SESSION['uye_id'];
$mesaj = "";


try {
    $kategoriler = $db->query("SELECT * FROM kategoriler")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $kategoriler = [];
}


if (isset($_POST['derse_katil'])) {
    $ders_id = $_POST['ders_id'];

   
    $kontenjanSorgu = $db->prepare("SELECT d.kontenjan, COUNT(r.id) as kayitli_sayisi 
                                    FROM dersler d 
                                    LEFT JOIN rezervasyonlar r ON d.id = r.ders_id AND r.durum = 'onayli'
                                    WHERE d.id = ?");
    $kontenjanSorgu->execute([$ders_id]);
    $durum = $kontenjanSorgu->fetch(PDO::FETCH_ASSOC);

    $kayitTuru = 'onayli';
    $uyariMetni = "Rezervasyonunuz onaylandı! Derse bekliyoruz.";

    
    if ($durum['kayitli_sayisi'] >= $durum['kontenjan']) {
        $kayitTuru = 'beklemede';
        $uyariMetni = "Kontenjan dolu! Yedek listeye eklendiniz. Yer açılırsa haber vereceğiz.";
    }

   
    $kontrol = $db->prepare("SELECT * FROM rezervasyonlar WHERE uye_id = ? AND ders_id = ?");
    $kontrol->execute([$uye_id, $ders_id]);

    if ($kontrol->rowCount() > 0) {
        $mesaj = '<div class="mesaj hatali">Zaten bu derse kaydınız var.</div>';
    } else {
        
        $ekle = $db->prepare("INSERT INTO rezervasyonlar (uye_id, ders_id, durum) VALUES (?, ?, ?)");
        $ekle->execute([$uye_id, $ders_id, $kayitTuru]);

        $renk = ($kayitTuru == 'onayli') ? 'basarili' : 'hatali';
        $mesaj = '<div class="mesaj ' . $renk . '">' . $uyariMetni . '</div>';
    }
}


$sql = "SELECT d.*, e.ad_soyad as egitmen_adi, k.kategori_adi,
        (SELECT COUNT(*) FROM rezervasyonlar WHERE ders_id = d.id AND durum = 'onayli') as dolu_yer
        FROM dersler d 
        JOIN egitmenler e ON d.egitmen_id = e.id 
        LEFT JOIN kategoriler k ON d.kategori_id = k.id 
        WHERE 1=1"; 
$params = [];


if (isset($_GET['ara']) && !empty($_GET['ara'])) {
    $sql .= " AND (d.ders_adi LIKE ? OR e.ad_soyad LIKE ?)";
    $params[] = "%" . $_GET['ara'] . "%";
    $params[] = "%" . $_GET['ara'] . "%";
}


if (isset($_GET['kategori_id']) && !empty($_GET['kategori_id'])) {
    $sql .= " AND d.kategori_id = ?";
    $params[] = $_GET['kategori_id'];
}

$sql .= " ORDER BY d.tarih_saat ASC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$dersler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ders Programı - Zirve Spor</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: var(--kutu-arkaplan);
        }

        th,
        td {
            padding: 15px;
            border: 1px solid var(--kenarlik);
            text-align: left;
            vertical-align: bottom;
        }

        th {
            background-color: var(--ana-renk);
            color: white;
        }

        .tablo-kalin {
            font-weight: bold;
        }

        .tablo-italik {
            font-style: italic;
        }

        .tablo-alti-cizili {
            text-decoration: underline;
        }

        .tablo-ustu-cizili {
            text-decoration: line-through;
            color: red;
        }

        .progress-bg {
            background-color: #ddd;
            height: 10px;
            width: 100px;
            border-radius: 5px;
            overflow: hidden;
            display: inline-block;
        }

        .progress-bar {
            height: 100%;
        }

        
        .filtre-alani {
            background: var(--kutu-arkaplan);
            padding: 15px;
            border: 1px solid var(--kenarlik);
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }

        .filtre-alani input,
        .filtre-alani select {
            padding: 10px;
            border: 1px solid var(--kenarlik);
            background-color: var(--arka-plan-rengi);
            
            color: var(--yazi-rengi);
            border-radius: 5px;
            min-width: 200px;
        }

        .filtre-alani button {
            padding: 10px 20px;
            background: var(--ana-renk);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="sayfa-duzeni">

        <header class="kutu baslik">
            <div class="logo-bolumu">
                <img src="resimler/logo.png" alt="Logo" class="logo">
                <h1>ZİRVE SPOR KOMPLEKSİ</h1>
            </div>
            <button id="tema-btn">🌙 Koyu Mod</button>
        </header>

        <nav class="kutu menu" style="display: flex; align-items: center; justify-content: space-between; padding-right: 10px;">
            <div>
                <a href="index.php">Ana Sayfa</a>
                <a href="dersler.php">Ders Programı</a>
                <a href="egitmenler.php">Eğitmenlerimiz</a>
                <a href="hakkimizda.php">Hakkımızda</a>
            </div>
            <div>
                <?php if (isset($_SESSION['uye_id'])): ?>
                    <a href="vki.php" style="margin-right:10px;">Profilim</a>
                    <span style="color: white; font-size: 14px; margin-right:10px;">👤 <?php echo $_SESSION['ad_soyad']; ?></span>
                    <a href="cikis.php" style="background-color: #d9534f; padding: 8px 15px; border-radius: 5px;">Çıkış</a>
                <?php endif; ?>
            </div>
        </nav>

        <main class="kutu orta" style="grid-column: 1 / -1;">
            <h2 style="text-align:center;">HAFTALIK DERS PROGRAMI </h2>
            <p style="text-align:center; margin-bottom:20px;">Kontenjan dolmadan yerini ayırt! Doluysa yedek listeye girersin.</p>

            <?php if ($mesaj != "") echo $mesaj; ?>

            <form class="filtre-alani" method="GET">
                <input type="text" name="ara" placeholder="Ders adı veya eğitmen ara..." value="<?php echo isset($_GET['ara']) ? htmlspecialchars($_GET['ara']) : ''; ?>">

                <select name="kategori_id">
                    <option value="">Tüm Kategoriler</option>
                    <?php foreach ($kategoriler as $kat): ?>
                        <option value="<?php echo $kat['id']; ?>" <?php echo (isset($_GET['kategori_id']) && $_GET['kategori_id'] == $kat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($kat['kategori_adi']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit"><i class="fa-solid fa-filter"></i> Filtrele</button>

                <?php if (isset($_GET['ara']) || isset($_GET['kategori_id'])): ?>
                    <a href="dersler.php" style="color:var(--vurgu-renk); font-weight:bold; text-decoration:none; font-size:0.9rem;">Temizle</a>
                <?php endif; ?>
            </form>
            <div class="tablo-kapsayici">
                <table>
                    <thead>
                        <tr>
                            <th>Ders Adı</th>
                            <th>Eğitmen</th>
                            <th>Tarih & Saat</th>
                            <th>Doluluk</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dersler as $ders):
                            
                            $oran = ($ders['dolu_yer'] / $ders['kontenjan']) * 100;
                            $renk = "green";
                            if ($oran > 50) $renk = "orange";
                            if ($oran >= 100) $renk = "red";

                            
                            $doluMu = ($ders['dolu_yer'] >= $ders['kontenjan']);

                            
                            $katAdi = !empty($ders['kategori_adi']) ? $ders['kategori_adi'] : '';
                        ?>
                            <tr class="ders-item">
                                <td class="tablo-kalin">
                                    <?php echo htmlspecialchars($ders['ders_adi']); ?>
                                    <?php if ($katAdi): ?>
                                        <br><span style="font-size:0.75rem; font-weight:normal; background:var(--vurgu-renk); color:white; padding:2px 6px; border-radius:4px;"><?php echo htmlspecialchars($katAdi); ?></span>
                                    <?php endif; ?>
                                </td>

                                <td class="tablo-italik"><?php echo htmlspecialchars($ders['egitmen_adi']); ?></td>

                                <td class="tablo-alti-cizili"><?php echo date("d.m.Y H:i", strtotime($ders['tarih_saat'])); ?></td>

                                <td>
                                    <div class="progress-bg">
                                        <div class="progress-bar" style="width: <?php echo $oran; ?>%; background-color: <?php echo $renk; ?>;"></div>
                                    </div>
                                    <small>(<?php echo $ders['dolu_yer']; ?>/<?php echo $ders['kontenjan']; ?>)</small>
                                </td>

                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="ders_id" value="<?php echo $ders['id']; ?>">

                                        <?php if ($doluMu): ?>
                                            <button type="submit" name="derse_katil" style="background-color: #e67e22; color:white; border:none; padding:5px 10px; cursor:pointer;">
                                                ⏳ Yedek
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" name="derse_katil" style="background-color: var(--ana-renk); color:white; border:none; padding:5px 10px; cursor:pointer;">
                                                ✅ Katıl
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (count($dersler) == 0): ?>
                            <tr>
                                <td colspan="5" style="text-align:center; padding:20px; color:#777;">Aradığınız kriterlere uygun ders bulunamadı.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>

        <footer class="kutu alt">
            <div class="footer-icerik">
                <div class="footer-kolon">
                    <h3>ZİRVE SPOR</h3>
                    <p>Şehrin en kapsamlı spor kompleksi. Uzman eğitmenler, modern ekipmanlar ve hijyenik ortam ile hedeflerinize ulaşın.</p>
                    <div class="sosyal-ikonlar">
                        <a href="https://instagram.com" target="_blank" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="https://twitter.com" target="_blank" title="X"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="https://facebook.com" target="_blank" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="https://youtube.com" target="_blank" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-kolon">
                    <h3>HIZLI ERİŞİM</h3>
                    <ul>
                        <li><a href="index.php">› Ana Sayfa</a></li>
                        <li><a href="dersler.php">› Ders Programı</a></li>
                        <li><a href="egitmenler.php">› Eğitmenlerimiz</a></li>
                        <li><a href="vki.php">› VKİ Hesapla</a></li>
                        <li><a href="kayit.php">› Kayıt Ol</a></li>
                        <li><a href="javascript:void(0)" onclick="haritaAc()">› İletişim & Konum Göster</a></li>
                        <li><a href="javascript:void(0)" onclick="sikayetAc()">› Şikayet & Öneri Kutusu</a></li>
                    </ul>
                </div>
                <div class="footer-kolon">
                    <h3>İLETİŞİM</h3>
                    <p>📍 <strong>Adres:</strong> Barbaros Bulvarı No:123, Beşiktaş / İstanbul</p>
                    <p>📞 <strong>Telefon:</strong> 0212 123 45 67</p>
                    <p>✉️ <strong>E-posta:</strong> bilgi@zirvespor.com</p>
                    <p>⏰ <strong>Çalışma Saatleri:</strong><br>Hafta içi: 07:00 - 23:00<br>Hafta sonu: 09:00 - 21:00</p>
                </div>
            </div>
            <div class="footer-alt-bar" style="display:flex; justify-content:space-between; align-items:center; padding: 10px 20px;">
                <p style="margin:0;">© 2025 Zirve Spor Kompleksi. Tüm Hakları Saklıdır.</p>

                <a href="#top" style="color:white; text-decoration:none; background:var(--vurgu-renk); padding:5px 10px; border-radius:5px; font-size:0.8rem;">
                    <i class="fa-solid fa-arrow-up"></i> Yukarı Çık
                </a>
            </div>
        </footer>
    </div>

    <div id="haritaModal" class="modal">
        <div class="modal-icerik">
            <span class="kapat-btn" onclick="haritaKapat()">×</span>
            <h2 style="color:var(--ana-renk);">📍 Salonumuzun Konumu</h2>
            <iframe src="https://maps.google.com/maps?q=Barbaros+Bulvarı+No:123+Beşiktaş+İstanbul&t=&z=15&ie=UTF8&iwloc=&output=embed" width="100%" height="300" style="border:0; border-radius:10px;" allowfullscreen="" loading="lazy"></iframe>
            <div style="margin-top: 15px;">
                <p style="color:var(--yazi-rengi);">Zirve Spor Kompleksi'ne bekliyoruz!</p>
                <button onclick="haritaKapat()" style="background-color: #d9534f; color: white; border: none; padding: 10px 30px; border-radius: 5px; cursor: pointer; font-size: 16px;">Kapat</button>
            </div>
        </div>
    </div>

    <div id="sikayetModal" class="modal">
        <div class="modal-icerik">
            <span class="kapat-btn" onclick="sikayetKapat()">×</span>
            <h2 style="color:var(--ana-renk);">💭 Görüşleriniz Değerli</h2>
            <p style="color:var(--yazi-rengi); margin-bottom:15px;">Hizmet kalitemizi artırmak için lütfen düşüncelerinizi paylaşın.</p>
            <form method="POST">
                <div style="margin-bottom:10px;">
                    <input type="text" name="gorus_ad" placeholder="Adınız (İsteğe bağlı)" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px; box-sizing:border-box;">
                </div>
                <div style="margin-bottom:10px;">
                    <select name="gorus_konu" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px; box-sizing:border-box;">
                        <option value="oneri">💡 Öneri</option>
                        <option value="sikayet">⚠️ Şikayet</option>
                        <option value="tesekkur">❤️ Teşekkür</option>
                        <option value="diger">Diğer</option>
                    </select>
                </div>
                <div style="margin-bottom:15px;">
                    <textarea name="gorus_mesaj" rows="4" placeholder="Mesajınızı buraya yazın..." required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px; box-sizing:border-box; resize:vertical;"></textarea>
                </div>
                <button type="submit" name="gorus_bildir" style="background-color: var(--vurgu-renk); color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight:bold; width:100%;">GÖNDER</button>
            </form>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>

</html>