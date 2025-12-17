<?php
session_start();

$girisYapildi = false;
$kullaniciAdi = "";

if (isset($_SESSION['uye_id']) || isset($_SESSION['egitmen_id'])) {
    $girisYapildi = true;
    if (isset($_SESSION['ad_soyad'])) {
        $kullaniciAdi = $_SESSION['ad_soyad'];
    } elseif (isset($_SESSION['egitmen_ad'])) {
        $kullaniciAdi = $_SESSION['egitmen_ad'];
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hakkımızda - Zirve Spor</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .hakkimizda-metin {
            text-align: left;
            line-height: 1.8;
            color: var(--yazi-rengi);
        }

        .hakkimizda-metin h3 {
            color: var(--ana-renk);
            border-bottom: 2px solid var(--vurgu-renk);
            display: inline-block;
            margin-top: 30px;
            margin-bottom: 15px;
        }

       
        .ozellik-kutu {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            text-align: center;
        }

        .ozellik {
            flex: 1;
            background: var(--kutu-arkaplan);
            padding: 15px;
            border-radius: 8px;
            border: 1px solid var(--kenarlik);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .ozellik i {
            font-size: 2rem;
            color: var(--vurgu-renk);
            margin-bottom: 10px;
        }

        
        .galeri-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 15px;
        }

        .galeri-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 5px;
            transition: transform 0.3s;
        }

        .galeri-item img:hover {
            transform: scale(1.05);
        }

        
        .yorum-kutu {
            background: var(--kutu-arkaplan);
            border-left: 4px solid var(--ana-renk);
            padding: 15px;
            margin-bottom: 15px;
            font-style: italic;
            font-size: 0.9rem;
            color: var(--yazi-rengi);
        }

        .yorum-isim {
            font-weight: bold;
            color: var(--ana-renk);
            display: block;
            margin-top: 5px;
            font-style: normal;
            text-align: right;
            font-size: 0.8rem;
        }

        @media (max-width: 768px) {
            .ozellik-kutu {
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


        <aside class="kutu sol yan-menu" style="text-align: center;">
            <img src="resimler/resim5.avif" style="width:100%; border-radius:10px; margin-bottom:15px;">
            <div style="background-color: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 5px; border: 1px solid #c8e6c9;">
                <h4 style="margin:0;"><i class="fa-solid fa-leaf"></i> Doğa Dostu</h4>
                <p style="font-size:12px; margin-top:5px;">Salonumuz güneş enerjisi ile aydınlatılmakta ve su tasarruflu duş sistemleri kullanılmaktadır.</p>
            </div>
            <br>
            <div style="background-color: #e3f2fd; color: #1565c0; padding: 10px; border-radius: 5px; border: 1px solid #bbdefb;">
                <h4 style="margin:0;"><i class="fa-solid fa-wifi"></i> Ücretsiz Wi-Fi</h4>
                <p style="font-size:12px; margin-top:5px;">Tüm üyelerimize sınırsız yüksek hızlı internet.</p>
            </div>
        </aside>

        <main class="kutu orta">
            <h2 style="color:var(--ana-renk); text-align:center;">KURUMSAL</h2>

            <div class="hakkimizda-metin">
                <p><strong>Zirve Spor Kompleksi</strong>, İstanbul'un kalbinde, sporu bir yaşam biçimi haline getirmek isteyenler için 2010 yılında kapılarını açtı. Başlangıçta küçük bir fitness stüdyosu olarak yola çıktığımız bu serüvende, bugün 3000m²'lik alanda olimpik havuz, crossfit alanı ve spa merkeziyle hizmet veren dev bir kompleks haline geldik.</p>

                <h3>VİZYONUMUZ</h3>
                <p>Teknolojiyi ve sporu birleştirerek Türkiye'nin en yenilikçi spor merkezi olmak. Sadece kas geliştirmek değil, üyelerimizin ruhsal ve bedensel sağlığını bütüncül bir yaklaşımla iyileştirmeyi hedefliyoruz.</p>

                <h3>TESİSLERİMİZDEN KARELER</h3>
                <p style="font-size:0.9rem; color:#666;">Salonumuzda son teknoloji Technogym ekipmanları kullanılmaktadır.</p>
                <div class="galeri-grid">
                    <div class="galeri-item"><img src="resimler/salon.jpg" alt="Salon"></div>
                    <div class="galeri-item"><img src="resimler/salon2.jpg" alt="Ders"></div>
                    <div class="galeri-item"><img src="resimler/salon3.jpg" alt="Ağırlık"></div>
                    <div class="galeri-item"><img src="resimler/havuz.jpg" alt="Havuz"></div>
                </div>

                <h3>NEDEN BİZİ SEÇMELİSİN?</h3>
                <div class="ozellik-kutu">
                    <div class="ozellik">
                        <i class="fa-solid fa-medal"></i>
                        <h4>Sertifikalı Kadro</h4>
                        <p style="font-size:13px;">Tamamı akademisyen kökenli 20+ eğitmen.</p>
                    </div>
                    <div class="ozellik">
                        <i class="fa-solid fa-heart-pulse"></i>
                        <h4>Sağlık Kontrolü</h4>
                        <p style="font-size:13px;">Her ay ücretsiz diyetisyen ve vücut analizi.</p>
                    </div>
                    <div class="ozellik">
                        <i class="fa-solid fa-shield-halved"></i>
                        <h4>Hijyen Garantisi</h4>
                        <p style="font-size:13px;">Her saat başı profesyonel temizlik.</p>
                    </div>
                </div>

                <h3>ÜYELERİMİZ NE DİYOR?</h3>
                <div class="yorum-kutu">
                    "Hayatımda gittiğim en temiz ve ilgili spor salonu. Özellikle Ahmet Hoca'nın pilates derslerini kaçırmayın!"
                    <span class="yorum-isim">- Elif Yılmaz (2 yıldır üye)</span>
                </div>
                <div class="yorum-kutu">
                    "Ekipmanlar çok yeni ve sıra beklemiyorsunuz. Konumu da Beşiktaş'ta olduğu için ulaşım çok rahat."
                    <span class="yorum-isim">- Mert Demir (6 aydır üye)</span>
                </div>
            </div>

            <div style="margin-top: 30px;">
                <h3 style="color:var(--ana-renk); text-align:center; margin-bottom:15px;">📊 RAKAMLARLA BİZ</h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; text-align: center;">
                    <div style="background:var(--ana-renk); color:white; padding:20px; border-radius:8px;">
                        <i class="fa-solid fa-users" style="font-size:2rem;"></i>
                        <h2 style="margin:5px 0;">1500+</h2>
                        <p style="font-size:0.9rem;">Mutlu Üye</p>
                    </div>
                    <div style="background:var(--vurgu-renk); color:white; padding:20px; border-radius:8px;">
                        <i class="fa-solid fa-dumbbell" style="font-size:2rem;"></i>
                        <h2 style="margin:5px 0;">500+</h2>
                        <p style="font-size:0.9rem;">Ekipman</p>
                    </div>
                    <div style="background:#27ae60; color:white; padding:20px; border-radius:8px;">
                        <i class="fa-solid fa-calendar-check" style="font-size:2rem;"></i>
                        <h2 style="margin:5px 0;">50+</h2>
                        <p style="font-size:0.9rem;">Haftalık Ders</p>
                    </div>
                    <div style="background:#8e44ad; color:white; padding:20px; border-radius:8px;">
                        <i class="fa-solid fa-trophy" style="font-size:2rem;"></i>
                        <h2 style="margin:5px 0;">12</h2>
                        <p style="font-size:0.9rem;">Yıllık Tecrübe</p>
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
                    <marquee scrollamount="8">📢 YAZA FİT GİR! %50 İNDİRİM! 🔥</marquee>
                </div>
                <div class="sayac-govde">
                    <h4>⏳ SON FIRSAT</h4>
                    <div id="sayac">00:00:00</div>
                    <a href="kayit.php" class="btn-kampanya">KAYIT OL</a>
                </div>
            </div>

            <div style="margin-top:20px; text-align:center;">
                <h4 style="color:var(--ana-renk);">📍 Çalışma Saatleri</h4>
                <ul style="list-style:none; padding:0; font-size:0.9rem; color:var(--yazi-rengi);">
                    <li style="border-bottom:1px solid var(--kenarlik); padding:5px;">Hafta İçi: 07:00 - 23:00</li>
                    <li style="border-bottom:1px solid var(--kenarlik); padding:5px;">Cumartesi: 09:00 - 21:00</li>
                    <li style="padding:5px;">Pazar: 10:00 - 20:00</li>
                </ul>
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