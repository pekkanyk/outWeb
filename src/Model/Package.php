<?php

namespace App\Model;

class Package
{
    private int $width;
    private int $depth;
    private int $weight;
    private int $height;
    private int $volume;
    
    public function __construct(int $width, int $depth, int $weight, int $height, int $volume) {
        $this->width = $width;
        $this->depth = $depth;
        $this->weight = $weight;
        $this->height = $height;
        $this->volume = $volume;
    }
    public function getWidth(): int {
        return $this->width;
    }

    public function getDepth(): int {
        return $this->depth;
    }

    public function getWeight(): int {
        return $this->weight;
    }

    public function getHeight(): int {
        return $this->height;
    }

    public function getVolume(): int {
        return $this->volume;
    }

    public function setWidth(int $width): void {
        $this->width = $width;
    }

    public function setDepth(int $depth): void {
        $this->depth = $depth;
    }

    public function setWeight(int $weight): void {
        $this->weight = $weight;
    }

    public function setHeight(int $height): void {
        $this->height = $height;
    }

    public function setVolume(int $volume): void {
        $this->volume = $volume;
    }


}
