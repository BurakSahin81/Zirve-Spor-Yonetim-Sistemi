<?php

if (!isset($_COOKIE['son_ziyaret'])) {
    setcookie('son_ziyaret', date("d.m.Y H:i:s"), time() + (86400 * 30), "/"); 
}
session_start();
require_once 'db.php';


if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'uye') {
        $_SESSION['uye_id'] = $_SESSION['user_id'];
        $_SESSION['ad_soyad'] = $_SESSION['name'];
    }
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'egitmen') {
        $_SESSION['egitmen_id'] = $_SESSION['user_id'];
        $_SESSION['egitmen_ad'] = $_SESSION['name'];
    }
}


$girisYapildi = false;
$kullaniciAdi = "";
$uye_id = 0;

if (isset($_SESSION['uye_id'])) {
    $girisYapildi = true;
    $kullaniciAdi = $_SESSION['ad_soyad'];
    $uye_id = $_SESSION['uye_id'];
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['gorus_bildir'])) {
    echo "<script>alert('Görüşünüz için teşekkürler! Mesajınız yönetime iletildi.');</script>";
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zirve Spor Kompleksi</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body id="top">

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
                <?php if ($girisYapildi): ?>
                    <?php if (isset($_SESSION['egitmen_id'])): ?>
                        <a href="egitmen_panel.php" style="background-color: #f39c12; margin-right:10px;">Ders Yönetimi</a>
                        <span style="color: white; font-size: 14px; margin-right:10px;">🎓 <?php echo $_SESSION['egitmen_ad']; ?></span>
                    <?php else: ?>
                        <a href="vki.php" style="margin-right:10px;">Profilim</a>
                        <span style="color: white; font-size: 14px; margin-right:10px;">👤 <?php echo $kullaniciAdi; ?></span>
                    <?php endif; ?>
                    <a href="cikis.php" style="background-color: #d9534f; padding: 8px 15px; border-radius: 5px;">Çıkış</a>
                <?php else: ?>
                    <a href="giris.php" style="background-color: #93bf85; color: white; padding: 10px 20px; border-radius: 5px; margin-right: 10px; text-decoration: none;">Giriş Yap</a>
                    <a href="kayit.php" style="background-color: var(--vurgu-renk); color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold;">Kayıt Ol</a>
                <?php endif; ?>
            </div>
        </nav>

        <aside class="kutu sol yan-menu">
            <div style="background-color: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; border: 1px solid #ffeeba; text-align: center;">
                <h4 style="margin:0 0 5px 0;">💡 Günün Sözü</h4>
                <p id="gunun-sozu" style="font-style: italic; font-size: 14px; margin:0; min-height:40px;">Yükleniyor...</p>
                <small id="soz-yazari" style="display:block; text-align:right; color:#666; margin-top:5px;"></small>
            </div>
            <div style="margin-top: 20px; border-top: 1px solid var(--kenarlik); padding-top: 15px; text-align: center;">
                <h4 style="color: #27ae60; margin-bottom: 10px;">🍏 Sağlık Köşesi</h4>
                <p style="font-size: 11px; color: var(--yazi-rengi); opacity:0.8;">Bilimsel verilerle sporun etkisi</p>

                <div style="background: white; padding: 5px; border-radius: 5px; border: 1px solid #ddd; margin-bottom: 15px;">
                    <p style="font-size: 11px; font-weight:bold; color:#d35400; margin:0 0 5px 0;">🔥 Kalori Yakımı Farkı</p>
                    <img src="resimler/analiz_kalori.png" alt="Kalori" style="width: 100%; height: auto; display: block;">
                </div>

                <div style="background: white; padding: 5px; border-radius: 5px; border: 1px solid #ddd;">
                    <p style="font-size: 11px; font-weight:bold; color:#c0392b; margin:0 0 5px 0;">❤️ Kalp Sağlığı İyileşmesi</p>
                    <img src="resimler/analiz_nabiz.png" alt="Nabız" style="width: 100%; height: auto; display: block;">
                </div>

                <div style="background: white; padding: 5px; border-radius: 5px; border: 1px solid #ddd; margin-top: 15px;">
                    <p style="font-size: 11px; font-weight:bold; color:#8e44ad; margin:0 0 5px 0;">💤 Sporun Uyku ve Strese Etkisi</p>
                    <img src="resimler/analiz_uyku_stres.png" alt="Uyku ve Stres Analizi" style="width: 100%; height: auto; display: block;">
                </div>

                <small style="display: block; margin-top: 10px; font-size: 9px; color: var(--yazi-rengi); opacity:0.6;">
                    *Python ile sağlık verileri analiz edilmiştir.
                </small>
            </div>
        </aside>

        <main class="kutu orta">
            <div class="slider-kapsayici">
                <div class="slide aktif-slide">
                    <img src="resimler/resim1.avif" alt="Spor Salonu">
                    <div class="slide-icerik">
                        <h2>HAYALLERİNDEKİ VÜCUDA <br> <span>ZİRVE SPOR</span> İLE ULAŞ!</h2>
                        <p>En modern ekipmanlar ve 3000m² geniş antrenman alanı ile sınırlarını zorla.</p>
                        <a href="kayit.php" class="slide-btn">HEMEN BAŞLA 🚀</a>
                    </div>
                </div>

                <div class="slide">
                    <img src="resimler/resim2.avif" alt="Kampanya">
                    <div class="slide-icerik">
                        <h2>🔥 YAZA HAZIRLIK KAMPANYASI</h2>
                        <p>İlk aya özel <strong>%50 İNDİRİM</strong> fırsatını kaçırma! Kontenjanlar dolmadan yerini ayırt.</p>
                        <a href="kayit.php" class="slide-btn" style="background:#ffeb3b; color:black;">FIRSATI YAKALA ⚡</a>
                    </div>
                </div>

                <div class="slide">
                    <img src="resimler/resim3.avif" alt="Grup Dersleri">
                    <div class="slide-icerik">
                        <h2>GRUP DERSLERİ İLE SOSYALLEŞ</h2>
                        <p>Pilates, Yoga, Zumba ve Spinning... Uzman eğitmenler eşliğinde eğlenerek zayıfla.</p>
                        <a href="dersler.php" class="slide-btn">PROGRAMI GÖR 📅</a>
                    </div>
                </div>

                <button class="slider-btn sol-btn" onclick="slideDegistir(-1)">❮</button>
                <button class="slider-btn sag-btn" onclick="slideDegistir(1)">❯</button>
            </div>

            <hr style="margin: 30px 0; border: 0; border-top: 2px dashed var(--kenarlik);">

            <div style="text-align:center; padding: 10px;">
                <h2 style="color:var(--ana-renk); margin-bottom: 10px;">Neden Zirve Spor?</h2>
                <p style="color:var(--yazi-rengi); opacity:0.8; max-width: 600px; margin: 0 auto;">
                    Sadece bir spor salonu değil, bir yaşam merkezi. Hedeflerine ulaşman için ihtiyacın olan her şey burada.
                </p>

                <div style="display:flex; justify-content:space-around; margin-top:30px; flex-wrap:wrap; gap:20px;">

                    <div style="text-align:center; flex:1; min-width:150px;">
                        <div style="background:var(--vurgu-renk); color:white; width:70px; height:70px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 15px auto; font-size:1.8rem; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                            <i class="fa-solid fa-dumbbell"></i>
                        </div>
                        <h4 style="margin:0 0 5px 0; color:var(--ana-renk);">Modern Ekipman</h4>
                        <p style="font-size:0.85rem; color:var(--yazi-rengi); opacity:0.7;">En yeni teknoloji fitness aletleri.</p>
                    </div>

                    <div style="text-align:center; flex:1; min-width:150px;">
                        <div style="background:var(--ana-renk); color:white; width:70px; height:70px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 15px auto; font-size:1.8rem; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                        <h4 style="margin:0 0 5px 0; color:var(--ana-renk);">Uzman Kadro</h4>
                        <p style="font-size:0.85rem; color:var(--yazi-rengi); opacity:0.7;">Sertifikalı ve deneyimli eğitmenler.</p>
                    </div>

                    <div style="text-align:center; flex:1; min-width:150px;">
                        <div style="background:#27ae60; color:white; width:70px; height:70px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 15px auto; font-size:1.8rem; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                            <i class="fa-solid fa-shower"></i>
                        </div>
                        <h4 style="margin:0 0 5px 0; color:var(--ana-renk);">Hijyenik & Ferah</h4>
                        <p style="font-size:0.85rem; color:var(--yazi-rengi); opacity:0.7;">Her zaman temiz, havadar ortam.</p>
                    </div>

                </div>
            </div>
        </main>

        <aside class="kutu sag">

            <?php if ($girisYapildi): ?>

                <div style="margin-bottom: 20px;">
                    <h3 style="border-bottom: 2px solid var(--vurgu-renk); padding-bottom: 5px;">🔥 Sana Özel</h3>
                    <?php
                    $oneriler = [];
                    $kategori_resimleri = [1 => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=100&q=60', 2 => 'https://images.unsplash.com/photo-1599058945522-28d584b6f0ff?w=100&q=60', 3 => 'https://images.unsplash.com/photo-1599901860904-17e6ed7083a0?w=100&q=60', 4 => 'https://images.unsplash.com/photo-1530549387789-4c1017266635?w=100&q=60', 5 => 'https://images.unsplash.com/photo-1622279457486-62dcc4a431d6?w=100&q=60'];

                    if (isset($db)) {
                        try {
                            if ($uye_id > 0) {
                                $sorgu = $db->prepare("SELECT d.kategori_id FROM rezervasyonlar r JOIN dersler d ON r.ders_id = d.id WHERE r.uye_id = ? ORDER BY r.tarih DESC LIMIT 1");
                                $sorgu->execute([$uye_id]);
                                $son_ders = $sorgu->fetch(PDO::FETCH_ASSOC);
                                if ($son_ders) {
                                    $kat_id = $son_ders['kategori_id'];
                                    $oneriler = $db->query("SELECT * FROM dersler WHERE kategori_id = $kat_id AND tarih_saat > NOW() LIMIT 4")->fetchAll(PDO::FETCH_ASSOC);
                                }
                            }
                            if (empty($oneriler)) {
                                $oneriler = $db->query("SELECT * FROM dersler WHERE tarih_saat > NOW() ORDER BY RAND() LIMIT 4")->fetchAll(PDO::FETCH_ASSOC);
                            }
                        } catch (Exception $e) {
                        }
                    }

                    if (!empty($oneriler)) {
                        echo '<div class="oneri-sidebar-list">';
                        foreach ($oneriler as $oneri) {
                            $kat_id = $oneri['kategori_id'] ?? 1;
                            $resim_url = $kategori_resimleri[$kat_id] ?? $kategori_resimleri[1];
                            echo '<a href="dersler.php" class="oneri-sidebar-kart"><img src="' . $resim_url . '" class="oneri-thumb" alt="Ders"><div class="oneri-info"><h5>' . htmlspecialchars($oneri['ders_adi']) . '</h5><span>📅 ' . date("d.m H:i", strtotime($oneri['tarih_saat'])) . '</span></div></a>';
                        }
                        echo '</div>';
                    } else {
                    ?>
                        <div style="text-align:center; padding:15px; background:var(--arka-plan-rengi); border:1px dashed var(--kenarlik); border-radius:8px;">
                            <i class="fa-regular fa-calendar-check" style="font-size:2rem; color:var(--vurgu-renk); margin-bottom:10px;"></i>
                            <p style="color:var(--yazi-rengi); font-size:0.9rem; margin:0 0 10px 0;">Şu an sana uygun yeni bir ders bulamadık.</p>
                            <a href="dersler.php" style="display:block; padding:8px; background:var(--ana-renk); color:white; text-decoration:none; border-radius:5px; font-size:0.85rem; font-weight:bold; margin-bottom:5px;">📅 Tüm Programı Gör</a>
                            <a href="vki.php" style="display:block; padding:8px; background:none; border:1px solid var(--ana-renk); color:var(--ana-renk); text-decoration:none; border-radius:5px; font-size:0.85rem; font-weight:bold;">⚖️ VKİ Analizi Yap</a>
                        </div>
                    <?php } ?>
                </div>

                <div style="background-color: var(--kutu-arkaplan); border: 1px solid var(--kenarlik); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <h4 style="margin:0 0 15px 0; color:var(--ana-renk); border-bottom:2px solid var(--vurgu-renk); padding-bottom:5px; display:inline-block;">
                        💬 Son Değerlendirmeler
                    </h4>

                    <div style="display:flex; flex-direction:column; gap:10px;">
                        <?php
                        if (isset($db)) {
                            try {
                                $sql_yorumlar = "SELECT y.yorum_metni, y.puan, u.ad_soyad as uye_adi, e.ad_soyad as hoca_adi 
                                                 FROM yorumlar y 
                                                 JOIN uyeler u ON y.uye_id = u.id 
                                                 JOIN egitmenler e ON y.ders_id = e.id 
                                                 ORDER BY y.id DESC LIMIT 3";
                                $son_yorumlar = $db->query($sql_yorumlar)->fetchAll(PDO::FETCH_ASSOC);

                                if ($son_yorumlar) {
                                    foreach ($son_yorumlar as $y) {
                                        $yildiz = str_repeat("★", $y['puan']);
                                        $gri_yildiz = str_repeat("★", 5 - $y['puan']);
                                        echo '
                                        <div style="background:var(--arka-plan-rengi); padding:10px; border-radius:5px; font-size:0.85rem; border-left:3px solid var(--vurgu-renk);">
                                            <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                                                <strong style="color:var(--ana-renk);">' . htmlspecialchars(substr($y['uye_adi'], 0, 15)) . '</strong>
                                                <small style="color:#ffbc00;">' . $yildiz . '<span style="color:#ddd;">' . $gri_yildiz . '</span></small>
                                            </div>
                                            <p style="margin:0; color:var(--yazi-rengi); font-style:italic; opacity:0.8;">"' . htmlspecialchars(substr($y['yorum_metni'], 0, 50)) . '..."</p>
                                            <small style="display:block; text-align:right; margin-top:5px; color:var(--yazi-rengi); opacity:0.6; font-size:0.7rem;">
                                                Eğitmen: ' . htmlspecialchars($y['hoca_adi']) . '
                                            </small>
                                        </div>';
                                    }
                                } else {
                                    echo '<p style="color:var(--yazi-rengi); font-size:0.9rem; text-align:center;">Henüz yorum yapılmamış.</p>';
                                }
                            } catch (Exception $e) {
                            }
                        }
                        ?>
                    </div>

                    <div style="text-align:center; margin-top:10px;">
                        <a href="egitmenler.php" style="font-size:0.8rem; text-decoration:none; color:var(--vurgu-renk); font-weight:bold;">Tümünü Gör →</a>
                    </div>
                </div>

            <?php else: ?>
                <div style="background-color: var(--ana-renk); color: white; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                    <h3 style="margin-top:0;">👋 Aramıza Katıl!</h3>
                    <p style="font-size:14px;">Size özel ders önerilerini ve liderlik tablosunu görmek için hemen giriş yapın.</p>
                    <a href="giris.php" style="display:block; background:#D35400; color:#F7F9F9; padding:10px; border-radius:5px; text-decoration:none; font-weight:bold; margin-top:10px;">Giriş Yap</a>
                    <p style="font-size:12px; margin-top:10px;">Üye değil misin? <a href="kayit.php" style="color:#ffeb3b;">Kayıt Ol</a></p>
                </div>
            <?php endif; ?>

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