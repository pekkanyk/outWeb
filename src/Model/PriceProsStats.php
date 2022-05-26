<?php

namespace App\Model;

class PriceProsStats
{
    private $minprice;
    private $maxprice;
    private $nimi;
    private $sumOut;
    private $sumNor;
    private $count;
    
    
    public function __construct($minprice, $maxprice, $sumOut, $sumNor,$count) {
        $this->minprice = $minprice;
        $this->maxprice = $maxprice;
        $this->nimi = $minprice ."...". $maxprice;
        $this->sumOut = $sumOut;
        $this->sumNor = $sumNor;
        $this->count = $count;
    }
    public function getCount() {
        return $this->count;
    }

        
    public function getMinprice() {
        return $this->minprice;
    }

    public function getMaxprice() {
        return $this->maxprice;
    }

    public function getNimi() {
        return $this->nimi;
    }

    public function getSumOut() {
        return $this->sumOut;
    }

    public function getSumNor() {
        return $this->sumNor;
    }

    public function setMinprice($minprice): void {
        $this->minprice = $minprice;
    }

    public function setMaxprice($maxprice): void {
        $this->maxprice = $maxprice;
    }

    public function setNimi($nimi): void {
        $this->nimi = $nimi;
    }

    public function setSumOut($sumOut): void {
        $this->sumOut = $sumOut;
    }

    public function setSumNor($sumNor): void {
        $this->sumNor = $sumNor;
    }


    
    

}
