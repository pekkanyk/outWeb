<?php

namespace App\Model;

class DbStats
{
    private $active_uniikit;
    private $active_count;
    private $active_count_A;
    private $active_count_B;
    private $active_count_C;
    private $active_count_D;
    private $active_days;
    private $active_daysUpdated;
    private $active_top10;
    private $deleted_uniikit;
    private $deleted_count;
    private $deleted_count_A;
    private $deleted_count_B;
    private $deleted_count_C;
    private $deleted_count_D;
    private $deleted_days;
    private $deleted_daysUpdated;
    private $deleted_top10;
    private $oldest;
    private $newest;
    private $avgId;
    private $oldest_deleted;
    private $newest_deleted;
    private $avgId_deleted;
    private $active_sumOut;
    private $deleted_sumOut;
    private $active_sumNor;
    private $deleted_sumNor;
    private $active_longest;
    private $deleted_longest;
    private $longestTop10;
  
    public function __construct() {
    
    }
    public function getActive_daysUpdated() {
        return $this->active_daysUpdated;
    }
    
    public function getLongestTop10() {
        return $this->longestTop10;
    }

    public function setLongestTop10($longestTop10): void {
        $this->longestTop10 = $longestTop10;
    }

        public function getDeleted_daysUpdated() {
        return $this->deleted_daysUpdated;
    }

    public function setActive_daysUpdated($active_daysUpdated): void {
        $this->active_daysUpdated = $active_daysUpdated;
    }

    public function setDeleted_daysUpdated($deleted_daysUpdated): void {
        $this->deleted_daysUpdated = $deleted_daysUpdated;
    }

        public function getActive_sumOut() {
        return $this->active_sumOut;
    }

    public function getDeleted_sumOut() {
        return $this->deleted_sumOut;
    }

    public function getActive_sumNor() {
        return $this->active_sumNor;
    }

    public function getDeleted_sumNor() {
        return $this->deleted_sumNor;
    }

    public function getActive_longest() {
        return $this->active_longest;
    }

    public function getDeleted_longest() {
        return $this->deleted_longest;
    }

    public function setActive_sumOut($active_sumOut): void {
        $this->active_sumOut = $active_sumOut;
    }

    public function setDeleted_sumOut($deleted_sumOut): void {
        $this->deleted_sumOut = $deleted_sumOut;
    }

    public function setActive_sumNor($active_sumNor): void {
        $this->active_sumNor = $active_sumNor;
    }

    public function setDeleted_sumNor($deleted_sumNor): void {
        $this->deleted_sumNor = $deleted_sumNor;
    }

    public function setActive_longest($active_longest): void {
        $this->active_longest = $active_longest;
    }

    public function setDeleted_longest($deleted_longest): void {
        $this->deleted_longest = $deleted_longest;
    }

        public function getOldest_deleted() {
        return $this->oldest_deleted;
    }

    public function getNewest_deleted() {
        return $this->newest_deleted;
    }

    public function getAvgId_deleted() {
        return $this->avgId_deleted;
    }

    public function setOldest_deleted($oldest_deleted): void {
        $this->oldest_deleted = $oldest_deleted;
    }

    public function setNewest_deleted($newest_deleted): void {
        $this->newest_deleted = $newest_deleted;
    }

    public function setAvgId_deleted($avgId_deleted): void {
        $this->avgId_deleted = $avgId_deleted;
    }

        public function getAvgId() {
        return $this->avgId;
    }

    public function setAvgId($avgId): void {
        $this->avgId = $avgId;
    }

        public function getActive_uniikit() {
        return $this->active_uniikit;
    }

    public function getActive_count() {
        return $this->active_count;
    }

    public function getActive_count_A() {
        return $this->active_count_A;
    }

    public function getActive_count_B() {
        return $this->active_count_B;
    }

    public function getActive_count_C() {
        return $this->active_count_C;
    }

    public function getActive_count_D() {
        return $this->active_count_D;
    }

    public function getActive_days() {
        return $this->active_days;
    }

    public function getActive_top10() {
        return $this->active_top10;
    }

    public function getDeleted_uniikit() {
        return $this->deleted_uniikit;
    }

    public function getDeleted_count() {
        return $this->deleted_count;
    }

    public function getDeleted_count_A() {
        return $this->deleted_count_A;
    }

    public function getDeleted_count_B() {
        return $this->deleted_count_B;
    }

    public function getDeleted_count_C() {
        return $this->deleted_count_C;
    }

    public function getDeleted_count_D() {
        return $this->deleted_count_D;
    }

    public function getDeleted_days() {
        return $this->deleted_days;
    }

    public function getDeleted_top10() {
        return $this->deleted_top10;
    }

    public function getOldest() {
        return $this->oldest;
    }

    public function getNewest() {
        return $this->newest;
    }

    public function setActive_uniikit($active_uniikit): void {
        $this->active_uniikit = $active_uniikit;
    }

    public function setActive_count($active_count): void {
        $this->active_count = $active_count;
    }

    public function setActive_count_A($active_count_A): void {
        $this->active_count_A = $active_count_A;
    }

    public function setActive_count_B($active_count_B): void {
        $this->active_count_B = $active_count_B;
    }

    public function setActive_count_C($active_count_C): void {
        $this->active_count_C = $active_count_C;
    }

    public function setActive_count_D($active_count_D): void {
        $this->active_count_D = $active_count_D;
    }

    public function setActive_days($active_days): void {
        $this->active_days = $active_days;
    }

    public function setActive_top10($active_top10): void {
        $this->active_top10 = $active_top10;
    }

    public function setDeleted_uniikit($deleted_uniikit): void {
        $this->deleted_uniikit = $deleted_uniikit;
    }

    public function setDeleted_count($deleted_count): void {
        $this->deleted_count = $deleted_count;
    }

    public function setDeleted_count_A($deleted_count_A): void {
        $this->deleted_count_A = $deleted_count_A;
    }

    public function setDeleted_count_B($deleted_count_B): void {
        $this->deleted_count_B = $deleted_count_B;
    }

    public function setDeleted_count_C($deleted_count_C): void {
        $this->deleted_count_C = $deleted_count_C;
    }

    public function setDeleted_count_D($deleted_count_D): void {
        $this->deleted_count_D = $deleted_count_D;
    }

    public function setDeleted_days($deleted_days): void {
        $this->deleted_days = $deleted_days;
    }

    public function setDeleted_top10($deleted_top10): void {
        $this->deleted_top10 = $deleted_top10;
    }

    public function setOldest($oldest): void {
        $this->oldest = $oldest;
    }

    public function setNewest($newest): void {
        $this->newest = $newest;
    }
    
    public function getTotalCount() {
        return $this->active_count+$this->deleted_count;
    }
    
    public function getTotalCount_A(){
        return $this->active_count_A+$this->deleted_count_A;
    }
    
    public function getTotalCount_B(){
        return $this->active_count_B+$this->deleted_count_B;
    }
    
    public function getTotalCount_C(){
        return $this->active_count_C+$this->deleted_count_C;
    }
    
    public function getTotalCount_D(){
        return $this->active_count_D+$this->deleted_count_D;
    }
    
}
