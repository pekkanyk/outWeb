<?php

namespace App\Model;

class PidStats
{
    private $active_kaOutPrice;
    private $deleted_kaOutPrice;
    private $active_kaAlennus;
    private $deleted_kaAlennus;
    private $active_kaAlennusProsentti;
    private $deleted_kaAlennusProsentti;
    private $active_kaActiveDays;
    private $deleted_kaActiveDays;
    private $pidCreated;
    private $pid;
    private $name;
    private $pidSize;
  
    public function __construct() {
        
    }
    public function getPidSize() {
        return $this->pidSize;
    }

    public function setPidSize($pidSize): void {
        $this->pidSize = $pidSize;
    }

        public function getName() {
        return $this->name;
    }

    public function setName($name): void {
        $this->name = $name;
    }

        public function getActive_kaOutPrice() {
        return $this->active_kaOutPrice;
    }

    public function getDeleted_kaOutPrice() {
        return $this->deleted_kaOutPrice;
    }

    public function getActive_kaAlennus() {
        return $this->active_kaAlennus;
    }

    public function getDeleted_kaAlennus() {
        return $this->deleted_kaAlennus;
    }

    public function getActive_kaAlennusProsentti() {
        return $this->active_kaAlennusProsentti;
    }

    public function getDeleted_kaAlennusProsentti() {
        return $this->deleted_kaAlennusProsentti;
    }

    public function getActive_kaActiveDays() {
        return $this->active_kaActiveDays;
    }

    public function getDeleted_kaActiveDays() {
        return $this->deleted_kaActiveDays;
    }

    public function getPidCreated() {
        return $this->pidCreated;
    }

    public function getPid() {
        return $this->pid;
    }

    public function setActive_kaOutPrice($active_kaOutPrice): void {
        $this->active_kaOutPrice = $active_kaOutPrice;
    }

    public function setDeleted_kaOutPrice($deleted_kaOutPrice): void {
        $this->deleted_kaOutPrice = $deleted_kaOutPrice;
    }

    public function setActive_kaAlennus($active_kaAlennus): void {
        $this->active_kaAlennus = $active_kaAlennus;
    }

    public function setDeleted_kaAlennus($deleted_kaAlennus): void {
        $this->deleted_kaAlennus = $deleted_kaAlennus;
    }

    public function setActive_kaAlennusProsentti($active_kaAlennusProsentti): void {
        $this->active_kaAlennusProsentti = $active_kaAlennusProsentti;
    }

    public function setDeleted_kaAlennusProsentti($deleted_kaAlennusProsentti): void {
        $this->deleted_kaAlennusProsentti = $deleted_kaAlennusProsentti;
    }

    public function setActive_kaActiveDays($active_kaActiveDays): void {
        $this->active_kaActiveDays = $active_kaActiveDays;
    }

    public function setDeleted_kaActiveDays($deleted_kaActiveDays): void {
        $this->deleted_kaActiveDays = $deleted_kaActiveDays;
    }

    public function setPidCreated($pidCreated): void {
        $this->pidCreated = $pidCreated;
    }

    public function setPid($pid): void {
        $this->pid = $pid;
    }


}
