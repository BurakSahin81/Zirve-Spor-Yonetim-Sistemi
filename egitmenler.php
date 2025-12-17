<?php
session_start();
require_once 'db.php';
require_once 'classes.php'; 

$mesaj = "";


if (isset($_POST['yorum_yap'])) {
    if (!isset($_SESSION['user_id']) || (isset($_SESSION['role']) && $_SESSION['role'] != 'uye')) {
        $mesaj = '<div class="mesaj hatali">Yorum yapmak için ÜYE girişi yapmalısınız!</div>';
    } else {
        $uye_id = $_SESSION['user_id'];
        $egitmen_id = $_POST['egitmen_id'];
        $yorum_metni = $_POST['yorum'];
        $puan = $_POST['puan'];

        try {
            $ekle = $db->prepare("INSERT INTO yorumlar (uye_id, ders_id, yorum_metni, puan, onay_durumu) VALUES (?, ?, ?, ?, 1)");
            $ekle->execute([$uye_id, $egitmen_id, $yorum_metni, $puan]);
            $mesaj = '<div class="mesaj basarili">Yorumunuz başarıyla eklendi!</div>';
        } catch (PDOException $e) {
            $mesaj = '<div class="mesaj hatali">Hata: ' . $e->getMessage() . '</div>';
        }
    }
}


$egitmenNesneleri = []; 
if (isset($db)) {
    try {
        $sorgu = $db->query("SELECT * FROM egitmenler ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

        
        foreach ($sorgu as $veri) {
            
            $egitmenNesneleri[] = new Egitmen(
                $veri['id'],
                $veri['ad_soyad'],
                $veri['uzmanlik'],
                $veri['resim_yolu'],
                $veri['hakkinda'] ?? 'Deneyimli spor eğitmeni.'
            );
        }
    } catch (Exception $e) {
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eğitmenler - Zirve Spor</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        
        .egitmen-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
            padding: 10px;
        }

        .egitmen-kart {
            background: var(--kutu-arkaplan);
            border: 1px solid var(--kenarlik);
            border-radius: 12px;
            overflow: hidden;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
        }

        .egitmen-kart:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .egitmen-kart img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-bottom: 3px solid var(--vurgu-renk);
        }

        .kart-icerik {
            padding: 20px;
            flex-grow: 1;
        }

        .buyuk-yazi {
            font-size: 1.4rem;
            font-weight: bold;
            color: var(--ana-renk);
            margin-bottom: 5px;
        }

        .uzmanlik-yazi {
            color: var(--vurgu-renk);
            font-weight: bold;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .kucuk-yazi {
            font-size: 0.85rem;
            color: var(--yazi-rengi);
            opacity: 0.8;
            margin-bottom: 15px;
        }

        .yorum-scroll {
            max-height: 150px;
            overflow-y: auto;
            margin-bottom: 15px;
            border: 1px solid var(--kenarlik);
            border-radius: 5px;
        }

        .yorum-kutusu {
            background-color: var(--arka-plan-rengi);
            color: var(--yazi-rengi);
            padding: 10px;
            margin: 5px;
            border-left: 3px solid var(--vurgu-renk);
            text-align: left;
            font-size: 0.9rem;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--kenarlik);
            background-color: var(--arka-plan-rengi);
            color: var(--yazi-rengi);
            border-radius: 5px;
            margin-bottom: 8px;
            box-sizing: border-box;
        }

        button[name="yorum_yap"] {
            width: 100%;
            padding: 10px;
            background: var(--ana-renk);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        button[name="yorum_yap"]:hover {
            background: #004494;
        }

        .mesaj {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }

        .basarili {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .hatali {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .egitmen-grid {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 0;
            }

            .buyuk-yazi {
                font-size: 1.2rem;
            }

            .egitmen-kart img {
                height: 200px;
            }

            .sayfa-duzeni {
                display: block;
            }

            .kutu {
                margin-bottom: 15px;
            }
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
                <a href="index.php" style="background:#d9534f; border-radius:5px; color: white; text-decoration: none; padding: 8px 15px;">Geri Dön</a>
            </div>
        </nav>

        <main class="kutu orta" style="grid-column: 1 / -1;">
            <h2 style="text-align:center; color:var(--ana-renk);">Uzman Kadromuz</h2>
            <p style="text-align:center; color:var(--yazi-rengi); opacity:0.8; margin-bottom: 30px;">Hedeflerinize ulaşmanızda size rehberlik edecek profesyonel ekibimiz.</p>

            <?php if ($mesaj) echo $mesaj; ?>

            <div class="egitmen-grid">
                <?php
                
                if (!empty($egitmenNesneleri)) {
                    foreach ($egitmenNesneleri as $hocaNesnesi) {
                       
                ?>
                        <div class="egitmen-kart">
                            <img src="<?php echo $hocaNesnesi->getResim(); ?>" alt="<?php echo $hocaNesnesi->getAdSoyad(); ?>">

                            <div class="kart-icerik">
                                <div class="buyuk-yazi"><?php echo $hocaNesnesi->getAdSoyad(); ?></div>
                                <div class="uzmanlik-yazi"><?php echo $hocaNesnesi->getUzmanlik(); ?></div>
                                <div class="kucuk-yazi">Sertifikalı Eğitmen</div>
                                <hr style="border:0; border-top:1px solid var(--kenarlik); margin:15px 0;">

                                <div class="yorum-scroll">
                                    <?php
                                    
                                    $sorgu = $db->prepare("SELECT y.*, u.ad_soyad FROM yorumlar y JOIN uyeler u ON y.uye_id = u.id WHERE y.ders_id = ? ORDER BY y.id DESC");
                                    $sorgu->execute([$hocaNesnesi->getId()]);
                                    if ($sorgu->rowCount() == 0) echo "<p style='padding:10px; color:var(--yazi-rengi); opacity:0.6; font-size:0.9rem;'>Henüz yorum yapılmamış.</p>";
                                    foreach ($sorgu as $ym):
                                    ?>
                                        <div class="yorum-kutusu">
                                            <strong><?php echo htmlspecialchars($ym['ad_soyad']); ?>:</strong><br>
                                            <?php echo htmlspecialchars($ym['yorum_metni']); ?><br>
                                            <small style="color:#ffbc00;"><?php echo str_repeat("★", $ym['puan']); ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'uye'): ?>
                                    <form method="POST">
                                        <input type="hidden" name="egitmen_id" value="<?php echo $hocaNesnesi->getId(); ?>">
                                        <input type="text" name="yorum" placeholder="Yorumunuz..." required>
                                        <select name="puan">
                                            <option value="5">⭐⭐⭐⭐⭐ (Mükemmel)</option>
                                            <option value="4">⭐⭐⭐⭐ (İyi)</option>
                                            <option value="3">⭐⭐⭐ (Orta)</option>
                                            <option value="2">⭐⭐ (Geliştirilmeli)</option>
                                            <option value="1">⭐ (Kötü)</option>
                                        </select>
                                        <button type="submit" name="yorum_yap">Yorumu Gönder</button>
                                    </form>
                                <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'egitmen'): ?>
                                    <p style="font-size:12px; color:blue; background:#e3f2fd; padding:5px; border-radius:4px;">Kendi profilinizi panelden yönetin.</p>
                                <?php else: ?>
                                    <p style="font-size:13px; color:#721c24; background:#f8d7da; padding:8px; border-radius:4px;">
                                        Yorum yapmak için <a href="giris.php" style="color:#721c24; font-weight:bold;">giriş yapın</a>.
                                    </p>
                                <?php endif; ?>

                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p style='text-align:center;'>Kayıtlı eğitmen bulunamadı.</p>";
                }
                ?>
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