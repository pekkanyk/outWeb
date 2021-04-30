<?php

namespace App\Model;

class SearchDate
{
    private $date;
    
    public function __construct() {
    }
    
    public function getDate() {
        return $this->date;
    }

    public function setDate($date): void {
        $this->date = $date;
    }


}
