<?php

namespace App\Model;

class SearchProducts
{
    private $activity;
    private $searchStr;
    private $orderBy;
    private $minprice;
    private $maxprice;
    private $direction;
    private $alkaen;
    private $asti;
    private $kl;
    
    public function __construct() {
        
    }
    public function getKl() {
        return $this->kl;
    }

    public function setKl($kl): void {
        $this->kl = $kl;
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

        public function getMinprice() {
        return $this->minprice;
    }

    public function getMaxprice() {
        return $this->maxprice;
    }

    public function setMinprice($minprice): void {
        $this->minprice = $minprice;
    }

    public function setMaxprice($maxprice): void {
        $this->maxprice = $maxprice;
    }

        public function getActivity() {
        return $this->activity;
    }

    public function getSearchStr() {
        return $this->searchStr;
    }

    public function getOrderBy() {
        return $this->orderBy;
    }

    public function getDirection() {
        return $this->direction;
    }

    public function setActivity($activity): void {
        $this->activity = $activity;
    }

    public function setSearchStr($searchStr): void {
        $this->searchStr = $searchStr;
    }

    public function setOrderBy($orderBy): void {
        $this->orderBy = $orderBy;
    }

    public function setDirection($direction): void {
        $this->direction = $direction;
    }


}
