

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";




CREATE TABLE `dersler` (
  `id` int(11) NOT NULL,
  `ders_adi` varchar(100) NOT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `egitmen_id` int(11) DEFAULT NULL,
  `tarih_saat` datetime NOT NULL,
  `kontenjan` int(11) DEFAULT 15
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `dersler` (`id`, `ders_adi`, `kategori_id`, `egitmen_id`, `tarih_saat`, `kontenjan`) VALUES
(1, 'Sabah Fitness', 1, 1, '2025-12-09 17:35:41', 20),
(2, 'HIIT Yağ Yakımı', 1, 4, '2025-12-09 17:35:41', 15),
(3, 'Total Body', 1, 2, '2025-12-10 17:35:41', 20),
(4, 'Advanced HIIT', 1, 5, '2025-12-11 17:35:41', 10),
(5, 'Mat Pilates', 3, 6, '2025-12-09 17:35:41', 12),
(6, 'Sunrise Yoga', 3, 9, '2025-12-09 17:35:41', 15),
(7, 'Reformer Grup', 3, 7, '2025-12-10 17:35:41', 5),
(8, 'Yin Yoga', 3, 10, '2025-12-11 17:35:41', 15),
(9, 'Boks Teknik', 2, 16, '2025-12-09 17:35:41', 10),
(10, 'Kick Boks 101', 2, 18, '2025-12-10 17:35:41', 12),
(11, 'Boks Kondisyon', 2, 17, '2025-12-11 17:35:41', 15),
(12, 'Yüzme Başlangıç', 4, 11, '2025-12-09 17:35:41', 8),
(13, 'Serbest Stil Teknik', 4, 12, '2025-12-10 17:35:41', 6),
(14, 'Su Altı Nefes', 4, 13, '2025-12-11 17:35:41', 8),
(15, 'Tenis Birebir', 5, 14, '2025-12-09 17:35:41', 2),
(16, 'Grup Tenis Dersi', 5, 15, '2025-12-10 17:35:41', 6);



CREATE TABLE `egitmenler` (
  `id` int(11) NOT NULL,
  `ad_soyad` varchar(100) NOT NULL,
  `uzmanlik` varchar(100) NOT NULL,
  `biyografi` text DEFAULT NULL,
  `resim_yolu` varchar(255) DEFAULT NULL,
  `eposta` varchar(100) DEFAULT NULL,
  `sifre` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `egitmenler` (`id`, `ad_soyad`, `uzmanlik`, `biyografi`, `resim_yolu`, `eposta`, `sifre`) VALUES
(1, 'Caner Demir', 'Fitness', 'Vücut geliştirme ve hipertrofi uzmanı. 8 yıl tecrübe.', NULL, 'canerdemir@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6'),
(2, 'Seda Yılmaz', 'Fitness', 'Kadınlara özel sıkılaşma ve güç antrenmanı koçu.', NULL, 'sedayılmaz@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6'),
(3, 'Berkant Dağ', 'Fitness', 'Fonksiyonel antrenman ve güç koçu.', NULL, 'berkantdağ@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6'),
(4, 'Merve Öz', 'HIIT Kardiyo', 'Yüksek yoğunluklu antrenmanlarla yağ yakımı uzmanı.', NULL, 'merveöz@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6'),
(5, 'Tolga Çevik', 'HIIT Kardiyo', 'Dayanıklılık ve kondisyon üzerine uzmanlaşmış atlet.', NULL, 'tolgaçevik@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6'),
(6, 'Zeynep Su', 'Pilates', 'Reformer ve Mat Pilates eğitmeni. Duruş bozuklukları uzmanı.', NULL, 'zeynepsu@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6'),
(7, 'Elif Kaya', 'Pilates', 'Hamile pilatesi ve esneklik konusunda sertifikalı.', NULL, 'elifkaya@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6'),
(8, 'Buse Naz', 'Pilates', 'Core gücü ve denge üzerine yoğunlaşan eğitmen.', NULL, 'busenaz@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6'),
(9, 'Arya Güneş', 'Yoga', 'Hatha ve Vinyasa Yoga eğitmeni. Meditasyon rehberi.', NULL, 'aryagüneş@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6'),
(10, 'Deniz Soylu', 'Yoga', 'Yin Yoga ve nefes terapisi uzmanı.', NULL, 'denizsoylu@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6'),
(11, 'Cem Çelik', 'Yüzme', 'Eski milli yüzücü. Çocuk ve yetişkin yüzme koçu.', NULL, 'cemçelik@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6'),
(12, 'Bahar Özdemir', 'Yüzme', 'Su altı teknikleri ve stil geliştirme uzmanı.', NULL, 'baharözdemir@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6'),
(13, 'Kaan Tepe', 'Yüzme', 'Triatlon sporcusu, dayanıklılık yüzme antrenörü.', NULL, 'kaantepe@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6'),
(14, 'Melis Arslan', 'Tenis', 'Profesyonel tenis kariyerine sahip, teknik geliştirme koçu.', NULL, 'melisarslan@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6'),
(15, 'Emre Destan', 'Tenis', 'Başlangıç ve orta seviye için taktiksel tenis eğitmeni.', NULL, 'emredestan@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6'),
(16, 'Hakan Şahin', 'Boks', 'Eski boks şampiyonu, savunma ve atak stratejisti.', NULL, 'hakanşahin@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6'),
(17, 'Murat Kılıç', 'Boks', 'Profesyonel boksör, kondisyon ve teknik antrenörü.', NULL, 'muratkılıç@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6'),
(18, 'Kerem Koç', 'Kick Boks', 'Muay Thai ve Kick Boks disiplinlerinde uzman.', NULL, 'keremkoç@zirve.com', '$2y$10$cQZNxFm8gKxZ5vPdx/wQ5.RFttloriVyAqeYQ5iyMb/0SzpvGWiD6');



CREATE TABLE `kategoriler` (
  `id` int(11) NOT NULL,
  `kategori_adi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `kategoriler` (`id`, `kategori_adi`) VALUES
(1, 'Fitness & Kondisyon'),
(2, 'Dövüş Sanatları'),
(3, 'Zihin & Beden'),
(4, 'Su Sporları'),
(5, 'Raket Sporları');



CREATE TABLE `rezervasyonlar` (
  `id` int(11) NOT NULL,
  `uye_id` int(11) DEFAULT NULL,
  `ders_id` int(11) DEFAULT NULL,
  `durum` enum('onayli','beklemede','iptal') DEFAULT 'onayli',
  `katilim_saglandi` tinyint(1) DEFAULT 0,
  `tarih` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `uyeler` (
  `id` int(11) NOT NULL,
  `ad_soyad` varchar(100) NOT NULL,
  `eposta` varchar(100) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `rol` enum('uye','admin') DEFAULT 'uye',
  `puan` int(11) DEFAULT 0,
  `kayit_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `uyeler` (`id`, `ad_soyad`, `eposta`, `sifre`, `rol`, `puan`, `kayit_tarihi`) VALUES
(3, 'İlknur Şahin', 'ilknur@zirve.com', '$2y$10$k7t1P8BD5pD9SaOEV2x3PuIwu8XcOsA2ga/A3r3pkhqh5mAG2pRPe', 'uye', 0, '2025-12-08 17:04:57'),
(5, 'ali şahin', 'ali@zirve.com', '$2y$10$Qgvl6cF50AcWH1BdeIPkpeoCym02cakM9t0LGEWnVVwcccyZIaMV6', 'uye', 0, '2025-12-09 20:36:25');


CREATE TABLE `vki_kayitlari` (
  `id` int(11) NOT NULL,
  `uye_id` int(11) NOT NULL,
  `boy` float NOT NULL,
  `kilo` float NOT NULL,
  `vki_degeri` float NOT NULL,
  `olcum_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `vki_takip` (
  `id` int(11) NOT NULL,
  `uye_id` int(11) DEFAULT NULL,
  `boy` float NOT NULL,
  `kilo` float NOT NULL,
  `vki_degeri` float DEFAULT NULL,
  `olcum_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `yoneticiler` (
  `id` int(11) NOT NULL,
  `ad_soyad` varchar(100) NOT NULL,
  `eposta` varchar(100) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `kayit_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `yoneticiler` (`id`, `ad_soyad`, `eposta`, `sifre`, `kayit_tarihi`) VALUES
(2, 'Admin (Burak Şahin)', 'admin@zirve.com', '$2y$10$br6W2hgY6zzALloM7ZSi..QTsdwnROJjkuF/1FOxMvvAZWJQ2vDry', '2025-12-09 19:18:45');



CREATE TABLE `yorumlar` (
  `id` int(11) NOT NULL,
  `uye_id` int(11) NOT NULL,
  `ders_id` int(11) NOT NULL,
  `yorum_metni` text NOT NULL,
  `puan` int(11) DEFAULT 5,
  `onay_durumu` tinyint(4) DEFAULT 1,
  `tarih` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




ALTER TABLE `dersler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `egitmen_id` (`egitmen_id`),
  ADD KEY `kategori_id` (`kategori_id`);


ALTER TABLE `egitmenler`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `kategoriler`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `rezervasyonlar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uye_id` (`uye_id`),
  ADD KEY `ders_id` (`ders_id`);


ALTER TABLE `uyeler`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `eposta` (`eposta`);


ALTER TABLE `vki_kayitlari`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uye_id` (`uye_id`);


ALTER TABLE `vki_takip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uye_id` (`uye_id`);


ALTER TABLE `yoneticiler`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `eposta` (`eposta`);


ALTER TABLE `yorumlar`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `dersler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;


ALTER TABLE `egitmenler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;


ALTER TABLE `kategoriler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;


ALTER TABLE `rezervasyonlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;


ALTER TABLE `uyeler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;


ALTER TABLE `vki_kayitlari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;


ALTER TABLE `vki_takip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;


ALTER TABLE `yoneticiler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;


ALTER TABLE `yorumlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;


ALTER TABLE `dersler`
  ADD CONSTRAINT `dersler_ibfk_1` FOREIGN KEY (`egitmen_id`) REFERENCES `egitmenler` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `dersler_ibfk_2` FOREIGN KEY (`kategori_id`) REFERENCES `kategoriler` (`id`) ON DELETE SET NULL;


ALTER TABLE `rezervasyonlar`
  ADD CONSTRAINT `rezervasyonlar_ibfk_1` FOREIGN KEY (`uye_id`) REFERENCES `uyeler` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rezervasyonlar_ibfk_2` FOREIGN KEY (`ders_id`) REFERENCES `dersler` (`id`) ON DELETE CASCADE;


ALTER TABLE `vki_kayitlari`
  ADD CONSTRAINT `vki_kayitlari_ibfk_1` FOREIGN KEY (`uye_id`) REFERENCES `uyeler` (`id`) ON DELETE CASCADE;


ALTER TABLE `vki_takip`
  ADD CONSTRAINT `vki_takip_ibfk_1` FOREIGN KEY (`uye_id`) REFERENCES `uyeler` (`id`) ON DELETE CASCADE;
COMMIT;

