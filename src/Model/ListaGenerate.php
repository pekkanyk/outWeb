<?php

namespace App\Model;

class ListaGenerate
{
    private $string;
    
    public function __construct() {
    }
    
    public function getString() {
        return $this->string;
    }

    public function setString($string): void {
        $this->string = $string;
    }


}
