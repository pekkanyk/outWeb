<?php

namespace App\Model;

class Search2Dates
{
    private $alku;
    private $loppu;
    /*
    public function __construct() {
        $this->loppu=new \DateTime('now', new \DateTimeZone('Europe/Helsinki'));
        $this->alku=(new \DateTime('now', new \DateTimeZone('Europe/Helsinki')))->sub(new \DateInterval('P30D'));
    }
     * 
     */
    public function __construct($alkaen='now',$asti='now') {
        /*
        $this->loppu=(new \DateTime($asti, new \DateTimeZone('Europe/Helsinki')))->add(new \DateInterval('P1D'));
        if ($alkaen!='now'){ $this->alku=(new \DateTime($alkaen, new \DateTimeZone('Europe/Helsinki')))->add(new \DateInterval('P1D')); }
        else {$this->alku=(new \DateTime('now', new \DateTimeZone('Europe/Helsinki')))->sub(new \DateInterval('P30D'));}
         * 
         */
        $this->loppu=new \DateTime($asti, new \DateTimeZone('Europe/Helsinki'));
        if ($alkaen!='now'){ $this->alku=new \DateTime($alkaen, new \DateTimeZone('Europe/Helsinki')); }
        else {$this->alku=(new \DateTime('now', new \DateTimeZone('Europe/Helsinki')))->sub(new \DateInterval('P30D'));}
    }
    public function getAlku() {
        return $this->alku;
    }

    public function getLoppu() {
        return $this->loppu;
    }

    public function setAlku($alku): void {
        $this->alku = $alku;
    }

    public function setLoppu($loppu): void {
        $this->loppu = $loppu;
    }


}
