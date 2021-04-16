<?php

namespace App\Model;

class Price
{
    private float $original;
    private Discount $discount;
    private float $discountAmount;
    private float $current;
    
    public function __construct(float $original, Discount $discount, float $discountAmount, float $current) {
        $this->original = $original;
        $this->discount = $discount;
        $this->discountAmount = $discountAmount;
        $this->current = $current;
    }

    
    public function getOriginal(): float {
        return $this->original;
    }

    public function getDiscount(): Discount {
        return $this->discount;
    }

    public function getDiscountAmount(): float {
        return $this->discountAmount;
    }

    public function getCurrent(): float {
        return $this->current;
    }

    public function setOriginal(float $original): void {
        $this->original = $original;
    }

    public function setDiscount(Discount $discount): void {
        $this->discount = $discount;
    }

    public function setDiscountAmount(float $discountAmount): void {
        $this->discountAmount = $discountAmount;
    }

    public function setCurrent(float $current): void {
        $this->current = $current;
    }


}
