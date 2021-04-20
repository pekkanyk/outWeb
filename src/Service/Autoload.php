<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use Rewieer\TaskSchedulerBundle\Task\AbstractScheduledTask;
use Rewieer\TaskSchedulerBundle\Task\Schedule;
use App\Controller\ReloadController;


class Autoload extends AbstractScheduledTask{
    private $reloadControler;
    public function __construct(ReloadController $reloadController) {
        $this->reloadControler = $reloadController;
    }
    
    protected function initialize(Schedule $schedule) {
        $schedule
        ->everyMinutes(10);
    }

    public function run() {
        $this->reloadControler->reloadProducts();
    }
}

