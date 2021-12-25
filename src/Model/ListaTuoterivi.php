<?php

namespace App\Model;

class ListaTuoterivi
{
    private $outid;
    private $tilausnro;
    private $outidVika;
    private $outidTokavika;
    private $tuote;
    private $hk;
    private $toimitus;
    public $hyllypaikka;

    
    public function __construct() {
        
    }
    public function getHyllypaikka() {
        return $this->hyllypaikka;
    }

    public function setHyllypaikka($hyllypaikka): void {
        $this->hyllypaikka = $hyllypaikka;
    }

        public function getToimitus() {
        return $this->toimitus;
    }

    public function setToimitus($toimitus): void {
        $this->toimitus = $toimitus;
    }

        public function getOutid() {
        return $this->outid;
    }

    public function getTilausnro() {
        return $this->tilausnro;
    }

    public function getOutidVika() {
        return $this->outidVika;
    }

    public function getOutidTokavika() {
        return $this->outidTokavika;
    }

    public function getTuote() {
        return $this->tuote;
    }

    public function getHk() {
        return $this->hk;
    }

    public function setOutid($outid): void {
        $this->outid = $outid;
    }

    public function setTilausnro($tilausnro): void {
        $this->tilausnro = $tilausnro;
    }

    public function setOutidVika($outidVika): void {
        $this->outidVika = $outidVika;
    }

    public function setOutidTokavika($outidTokavika): void {
        $this->outidTokavika = $outidTokavika;
    }

    public function setTuote($tuote): void {
        $this->tuote = $tuote;
    }

    public function setHk($hk): void {
        $this->hk = $hk;
    }
    
}
