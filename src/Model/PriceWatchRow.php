<?php

namespace App\Model;

class PriceWatchRow
{
    private $pid;
    private $nimi;
    private $halvin;
    private $limit;
    
    public function __construct($pid, $nimi, $halvin, $limit) {
        $this->pid = $pid;
        $this->nimi = $nimi;
        $this->halvin = $halvin;
        $this->limit = $limit;
    }

    
    public function getPid() {
        return $this->pid;
    }

    public function getNimi() {
        return $this->nimi;
    }

    public function getHalvin() {
        return $this->halvin;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function setPid($pid): void {
        $this->pid = $pid;
    }

    public function setNimi($nimi): void {
        $this->nimi = $nimi;
    }

    public function setHalvin($halvin): void {
        $this->halvin = $halvin;
    }

    public function setLimit($limit): void {
        $this->limit = $limit;
    }


}
