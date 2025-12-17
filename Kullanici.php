<?php


class Kullanici {
    private $db;

    
    public function __construct($veritabani_baglantisi) {
        $this->db = $veritabani_baglantisi;
    }

   
    public function kayitOl($adSoyad, $eposta, $sifre) {
        try {
            
            $sifreli_sifre = password_hash($sifre, PASSWORD_DEFAULT);

            
            $sorgu = $this->db->prepare("INSERT INTO uyeler (ad_soyad, eposta, sifre) VALUES (?, ?, ?)");
            $sorgu->execute([$adSoyad, $eposta, $sifreli_sifre]);

            return true; 
        } catch (PDOException $e) {
            
            return false; 
        }
    }

    
    public function girisYap($eposta, $sifre) {
        $sorgu = $this->db->prepare("SELECT * FROM uyeler WHERE eposta = ?");
        $sorgu->execute([$eposta]);
        $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

        
        if ($kullanici && password_verify($sifre, $kullanici['sifre'])) {
            return $kullanici; 
        } else {
            return false; 
        }
    }
}
?>