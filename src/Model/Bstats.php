<?php

namespace App\Model;

class Bstats
{
    private $summa;
    private $lukumaara;
    private $alkaen;
    private $asti;
   
    public function __construct() {
        $this->summa = 0.0;
        $this->lukumaara = 0;
    }
    
    public function getSumma() {
        return $this->summa;
    }

    public function getLukumaara() {
        return $this->lukumaara;
    }

    public function setSumma($summa): void {
        $this->summa = $summa;
    }

    public function setLukumaara($lukumaara): void {
        $this->lukumaara = $lukumaara;
    }
    
    public function getAlkaen() {
        return $this->alkaen;
    }

    public function getAsti() {
        return $this->asti;
    }

    public function setAlkaen($alkaen): void {
        $this->alkaen = $alkaen;
    }

    public function setAsti($asti): void {
        $this->asti = $asti;
    }





}
