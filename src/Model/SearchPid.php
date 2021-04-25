<?php

namespace App\Model;

class SearchPid
{
    private $pid;
    
    public function __construct() {
    }
    
    public function getPid() {
        return $this->pid;
    }

    public function setPid($pid): void {
        $this->pid = $pid;
    }



}
