<?php

abstract class Kisi {
    protected $ad_soyad; 

    public function __construct($ad_soyad) {
        $this->ad_soyad = $ad_soyad;
    }
    
    
    public function getAdSoyad() { return htmlspecialchars($this->ad_soyad); }
}


class Egitmen extends Kisi {
    private $id;
    private $uzmanlik;
    private $resim;
    private $hakkinda;

    public function __construct($id, $ad_soyad, $uzmanlik, $resim, $hakkinda) {
        parent::__construct($ad_soyad); 
        $this->id = $id;
        $this->uzmanlik = $uzmanlik;
        $this->resim = !empty($resim) ? $resim : "https://cdn-icons-png.flaticon.com/512/3135/3135715.png";
        $this->hakkinda = $hakkinda;
    }

    public function getId() { return $this->id; }
    public function getUzmanlik() { return htmlspecialchars($this->uzmanlik); }
    public function getResim() { return $this->resim; }
}
?>