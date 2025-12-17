<?php
session_start();
require_once 'db.php'; 

$uye_id = $_SESSION['uye_id'];
$mesaj = "";
$vki_sonuc = "";
$vki_durum = "";
$renk_sinifi = "";


if (!isset($_SESSION['uye_id'])) {
    header("Location: giris.php");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hesapla'])) {
    $boy = $_POST['boy'];
    $kilo = $_POST['kilo'];

    if ($boy > 0 && $kilo > 0) {
        $boy_m = $boy / 100;
        $vki = $kilo / ($boy_m * $boy_m);
        $vki_formatli = number_format($vki, 1);

        try {
            $stmt = $db->prepare("INSERT INTO vki_kayitlari (uye_id, boy, kilo, vki_degeri) VALUES (?, ?, ?, ?)");
            $stmt->execute([$uye_id, $boy, $kilo, $vki_formatli]);
            $mesaj = "<div style='color:green; margin-bottom:10px;'>✅ Ölçüm kaydedildi!</div>";
        } catch (PDOException $e) {
            $mesaj = "<div style='color:red;'>Hata: " . $e->getMessage() . "</div>";
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['iptal_et'])) {
    $rez_id = $_POST['rez_id'];
    try {
        $sil = $db->prepare("DELETE FROM rezervasyonlar WHERE id = ? AND uye_id = ?");
        $sil->execute([$rez_id, $uye_id]);
        $mesaj = "<div style='color:orange; margin-bottom:10px;'>🗑️ Randevu iptal edildi.</div>";
    } catch (PDOException $e) {
        $mesaj = "<div style='color:red;'>İptal edilemedi.</div>";
    }
}


$sonOlcum = $db->query("SELECT * FROM vki_kayitlari WHERE uye_id = $uye_id ORDER BY olcum_tarihi DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$gecmis_olcumler = $db->query("SELECT * FROM vki_kayitlari WHERE uye_id = $uye_id ORDER BY olcum_tarihi DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);


$sql_randevu = "SELECT r.id as rez_id, d.ders_adi, d.tarih_saat, e.ad_soyad 
                FROM rezervasyonlar r 
                LEFT JOIN dersler d ON r.ders_id = d.id 
                LEFT JOIN egitmenler e ON d.egitmen_id = e.id 
                WHERE r.uye_id = ? 
                ORDER BY d.tarih_saat DESC";
$stmt = $db->prepare($sql_randevu);
$stmt->execute([$uye_id]);
$aktif_randevular = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilim - Zirve Spor</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        
        .profil-govde {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            margin-top: 20px;
        }

        .profil-sol,
        .profil-sag {
            flex: 1;
            
            background: var(--kutu-arkaplan);
            border: 1px solid var(--kenarlik);
            border-radius: 8px;
            padding: 15px;
        }

        
        .vki-sonuc-kutusu {
            
            background-color: rgb(var(--arka-plan-rengi));
            color: var(--yazi-rengi);
            border: 1px solid var(--kenarlik);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .vki-sonuc-kutusu h3,
        .vki-sonuc-kutusu small {
            color: var(--yazi-rengi);
        }

        
        .form-grup input {
            width: 100%;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
            
            background-color: rgb(var(--arka-plan-rengi));
            color: var(--yazi-rengi);
            border: 1px solid var(--kenarlik);
            box-sizing: border-box;
        }

        
        .gecmis-tablo th,
        .gecmis-tablo td {
            padding: 8px;
            border-bottom: 1px solid var(--kenarlik);
            text-align: center;
            color: var(--yazi-rengi);
        }

        .gecmis-tablo th {
            color: var(--vurgu-renk);
            font-weight: bold;
            border-bottom: 2px solid var(--kenarlik);
        }

        
        .randevu-karti {
            
            background-color: rgb(var(--arka-plan-rengi)) !important;
            border: 1px solid var(--kenarlik);
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border-left: 4px solid var(--vurgu-renk);
        }

        .ders-listesi-kapsayici {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 5px;
        }

        @media (max-width: 768px) {
            .profil-govde {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <div class="sayfa-duzeni">
        <header class="kutu baslik">
            <div class="logo-bolumu">
                <img src="resimler/logo.png" alt="Zirve Spor" class="logo" onerror="this.style.display='none'">
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

        <aside class="kutu sol yan-menu" style="text-align: center;">
            <h3 style="border-bottom: 2px solid var(--vurgu-renk); padding-bottom: 5px;">📉 VKİ Hesapla</h3>
            <form method="POST">
                <div class="form-grup">
                    <input type="number" name="boy" placeholder="Boy (cm)" required>
                </div>
                <div class="form-grup">
                    <input type="number" name="kilo" placeholder="Kilo (kg)" required>
                </div>
                <button type="submit" name="hesapla" class="btn-kayit" style="width:100%; padding:10px; background:var(--ana-renk); color:white; border:none; border-radius:5px; cursor:pointer;">HESAPLA</button>
            </form>
            <hr style="margin: 20px 0; border-color: var(--kenarlik);">

            <h4>📋 Geçmiş Kayıtlar</h4>
            <?php if (count($gecmis_olcumler) > 0): ?>
                <table class="gecmis-tablo">
                    <tr>
                        <th>Tarih</th>
                        <th>Kilo</th>
                        <th>VKİ</th>
                    </tr>
                    <?php foreach ($gecmis_olcumler as $kayit): ?>
                        <tr>
                            <td><?php echo date("d.m", strtotime($kayit['olcum_tarihi'])); ?></td>
                            <td><?php echo $kayit['kilo']; ?></td>
                            <td><strong><?php echo $kayit['vki_degeri']; ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <small>Kayıt yok.</small>
            <?php endif; ?>
        </aside>

        <main class="kutu orta">

            <div style="text-align:center;">
                <h2 style="color:var(--ana-renk); margin-bottom:5px;">Profil Paneli</h2>
                <p>Merhaba, <strong><?php echo $_SESSION['ad_soyad']; ?></strong> 👋</p>
                <?php if ($mesaj) echo $mesaj; ?>
            </div>

            <div class="profil-govde">

                <div class="profil-sol">
                    <div class="vki-sonuc-kutusu" style="margin-top:0;">
                        <?php if ($sonOlcum):
                            $vki = $sonOlcum['vki_degeri'];
                            $durum = ($vki < 18.5) ? "Zayıf" : (($vki < 25) ? "Normal" : (($vki < 30) ? "Fazla Kilolu" : "Obez"));
                            $renk = ($vki < 18.5) ? "zayif" : (($vki < 25) ? "normal" : (($vki < 30) ? "kilolu" : "obez"));
                        ?>
                            <h3>Mevcut Durumun:</h3>
                            <h1 style="font-size: 3rem; margin: 10px 0; color:var(--yazi-rengi);"><?php echo $vki; ?></h1>
                            <div class="vki-cubuk-arkaplan">
                                <div class="vki-cubuk <?php echo $renk; ?>" style="width: <?php echo ($vki > 40 ? 100 : ($vki / 40) * 100); ?>%;">
                                    <?php echo $durum; ?>
                                </div>
                            </div>
                            <p><small>Sağlıklı yaşam için spora devam!</small></p>
                        <?php else: ?>
                            <p>Henüz bir ölçüm yapmadın. <br> Sol taraftan boy ve kilonu girerek başlayabilirsin. 👈</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="profil-sag">
                    <h3 style="border-bottom: 2px solid var(--vurgu-renk); padding-bottom: 5px; margin-top:0;">📅 Derslerim</h3>

                    <div class="ders-listesi-kapsayici">
                        <?php if (count($aktif_randevular) > 0): ?>
                            <?php foreach ($aktif_randevular as $ran): ?>
                                <div class="randevu-karti">
                                    <h4 style="margin:0; color:var(--ana-renk);"><?php echo $ran['ders_adi']; ?></h4>
                                    <div style="font-size:12px; margin:5px 0; color:var(--yazi-rengi);">
                                        <i class="fa-solid fa-user-tie"></i> <?php echo $ran['ad_soyad']; ?> <br>
                                        <i class="fa-regular fa-clock"></i> <?php echo date("d.m.Y H:i", strtotime($ran['tarih_saat'])); ?>
                                    </div>
                                    <form method="POST" onsubmit="return confirm('İptal etmek istiyor musun?');">
                                        <input type="hidden" name="rez_id" value="<?php echo $ran['rez_id']; ?>">
                                        <button type="submit" name="iptal_et" style="background:none; border:none; color:#e74c3c; cursor:pointer; font-weight:bold; font-size:12px; padding:0;">
                                            <i class="fa-solid fa-xmark"></i> İptal Et
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="text-align:center; padding:20px 0; color:var(--yazi-rengi); opacity:0.7;">
                                <p>📭 Henüz kayıtlı ders yok.</p>
                                <a href="dersler.php" style="font-weight:bold; color:var(--vurgu-renk);">Ders Seç</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </main>

        <aside class="kutu sag">
            <div class="kampanya-kutu">
                <div class="kampanya-resim-alani">
                    <img src="resimler/resim4.avif" alt="Kampanya" class="kampanya-resim">
                </div>
                <div class="marquee-alani">
                    <marquee scrollamount="8" direction="left">
                        📢 YAZA FİT GİR! İLK AY %50 İNDİRİM FIRSATINI KAÇIRMA! 🔥 SON GÜNLER! 🔥
                    </marquee>
                </div>
                <div class="sayac-govde">
                    <h4>⏳ SONA KALAN SÜRE</h4>
                    <div id="sayac">00:00:00</div>
                    <a href="kayit.php" class="btn-kampanya">HEMEN BAŞVUR 👉</a>
                </div>
            </div>

            <div style="background-color: #fff3cd; color: #856404; margin-top: 20px; padding: 10px; border-radius: 5px; border: 1px solid #ffeeba; text-align: center;">
                <h4 style="margin:0 0 5px 0;">💡 Günün Sözü</h4>
                <p id="gunun-sozu" style="font-style: italic; font-size: 14px; margin:0; min-height:40px;">Yükleniyor...</p>
                <small id="soz-yazari" style="display:block; text-align:right; color:#666; margin-top:5px;"></small>
            </div>
        </aside>

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