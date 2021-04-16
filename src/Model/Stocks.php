<?php

namespace App\Model;

class Stocks
{
    private Web $web;
    
    public function __construct(Web $web) {
        $this->web = $web;
    }
    public function getWeb(): Web {
        return $this->web;
    }

    public function setWeb(Web $web): void {
        $this->web = $web;
    }


}
