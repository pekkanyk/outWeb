<?php

namespace App\Model;
use App\Entity\OutletTuote;

class WeekStats
{
    private $alku;
    private $poistuneet;
    private $uudet;
    private $summa_poistuneet;
    private $summa_uudet;
    private $summa_norPrice;
    
    public function __construct($alku,$poistuneet,$uudet) {
        $this->alku = $alku;
        $this->poistuneet = $poistuneet;
        $this->uudet = $uudet;
        $this->summa_poistuneet =0;
        $this->summa_uudet = 0;
        $this->summa_norPrice = 0;
    }
    
    public function getSumma_norPrice() {
        return $this->summa_norPrice;
    }

    public function setSumma_norPrice($summa_norPrice): void {
        $this->summa_norPrice = $summa_norPrice;
    }

        
    public function getAlku() {
        return $this->alku;
    }


    public function getPoistuneet() {
        return $this->poistuneet;
    }

    public function getUudet() {
        return $this->uudet;
    }

    public function setAlku($alku): void {
        $this->alku = $alku;
    }

    public function setPoistuneet($poistuneet): void {
        $this->poistuneet = $poistuneet;
    }

    public function setUudet($uudet): void {
        $this->uudet = $uudet;
    }

    public function getSumma_poistuneet() {
        return $this->summa_poistuneet;
    }

    public function getSumma_uudet() {
        return $this->summa_uudet;
    }

    public function setSumma_poistuneet($summa_poistuneet): void {
        $this->summa_poistuneet = $summa_poistuneet;
    }

    public function setSumma_uudet($summa_uudet): void {
        $this->summa_uudet = $summa_uudet;
    }

    public function alku(){
        return $this->alku->format("d.m.Y");
    }
    
    public function loppu(){
        return $this->alku->add(new \DateInterval('P6D'))->format("d.m.Y");
    }
    
    public function week(){
        return $this->alku->format("W / o");
    }
    
    public function aleProsentti(){
        if ($this->summa_norPrice != 0){
            return 100*(1-($this->summa_poistuneet/$this->summa_norPrice));
        }
        else{
            return 0.0;
        }
    }

}
