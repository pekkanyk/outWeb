<?php

namespace App\Model;

class Availability
{
    private boolean $hasStock;
    private boolean $isEOL;
    private Stocks $stocks;
    private boolean $isPurchasable;
    
    public function __construct(boolean $hasStock, boolean $isEOL, Stocks $stocks, boolean $isPurchasable) {
        $this->hasStock = $hasStock;
        $this->isEOL = $isEOL;
        $this->stocks = $stocks;
        $this->isPurchasable = $isPurchasable;
    }
    
    public function getHasStock(): boolean {
        return $this->hasStock;
    }

    public function getIsEOL(): boolean {
        return $this->isEOL;
    }

    public function getStocks(): Stocks {
        return $this->stocks;
    }

    public function getIsPurchasable(): boolean {
        return $this->isPurchasable;
    }

    public function setHasStock(boolean $hasStock): void {
        $this->hasStock = $hasStock;
    }

    public function setIsEOL(boolean $isEOL): void {
        $this->isEOL = $isEOL;
    }

    public function setStocks(Stocks $stocks): void {
        $this->stocks = $stocks;
    }

    public function setIsPurchasable(boolean $isPurchasable): void {
        $this->isPurchasable = $isPurchasable;
    }


    }
