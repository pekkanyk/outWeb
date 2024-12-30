<?php

namespace App\Model;

class Top10Row
{
    private $pid;
    private $name;
    private $count;
    private $lastDel;
    private $size;
    
    public function __construct($pid, $name, $count,$lastDel,$size) {
        $this->pid = $pid;
        $this->name = $name;
        $this->count = $count;
        $this->lastDel = $lastDel;
        $this->size = $size;
    }
    public function getPid() {
        return $this->pid;
    }

    public function getName() {
        return $this->name;
    }

    public function getCount() {
        return $this->count;
    }

    public function setPid($pid): void {
        $this->pid = $pid;
    }

    public function setName($name): void {
        $this->name = $name;
    }

    public function setCount($count): void {
        $this->count = $count;
    }
    
    public function getLastDel() {
        return $this->lastDel;
    }

    public function setLastDel($lastDel): void {
        $this->lastDel = $lastDel;
    }
    public function getSize() {
        return $this->size;
    }

    public function setSize($size): void {
        $this->size = $size;
    }





}
