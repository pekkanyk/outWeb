<?php

namespace App\Model;

class ListaHyllypaikka
{
    private $name;
    private $tuoterivit;
    
    public function __construct() {
        
    }
    
    public function getName() {
        return $this->name;
    }

    public function getTuoterivit() {
        return $this->tuoterivit;
    }

    public function setName($name): void {
        $this->name = $name;
    }

    public function setTuoterivit($tuoterivit): void {
        $this->tuoterivit = $tuoterivit;
    }


}
