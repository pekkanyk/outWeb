<?php

namespace App\Model;

class Top10Row
{
    private $pid;
    private $name;
    private $count;
    private $lastDel;
    
    public function __construct($pid, $name, $count,$lastDel) {
        $this->pid = $pid;
        $this->name = $name;
        $this->count = $count;
        $this->lastDel = $lastDel;
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



}
