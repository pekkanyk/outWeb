<?php

namespace App\Model;

class Web
{
    private int $stock;
    
    public function __construct(int $stock) {
        $this->stock = $stock;
    }
    public function getStock(): int {
        return $this->stock;
    }

    public function setStock(int $stock): void {
        $this->stock = $stock;
    }


}
