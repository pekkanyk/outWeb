<?php

namespace App\Model;

class vkPage
{
    private Product $products;
    public function __construct(Product $products) {
        $this->products = $products;
    }
    public function getProducts(): Product {
        return $this->products;
    }

    public function setProducts(Product $products): void {
        $this->products = $products;
    }


    
}
