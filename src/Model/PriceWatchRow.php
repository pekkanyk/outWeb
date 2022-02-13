<?php

namespace App\Model;

class PriceWatchRow
{
    private $pid;
    private $nimi;
    private $halvin;
    private $limit;
    private $armed;
    
    public function __construct($pid, $nimi, $halvin, $limit,$armed) {
        $this->pid = $pid;
        $this->nimi = $nimi;
        $this->halvin = $halvin;
        $this->limit = $limit;
        $this->armed = $armed;
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
    
    public function getArmed() {
        return $this->armed;
    }

    public function setArmed($armed): void {
        $this->armed = $armed;
    }

}
