<?php

namespace App\Model;

class Product
{
    private int $tax;
    private int $warranty;
    private int $id;
    private string $condition;
    private string $product_name;
    private int $state;
    private float $price_without_tax;
    private string $product_extra_info;
    private float $price_with_tax;
    private int $pid;
    private string $manufacturers_code;
    
    public function __construct(int $tax, int $warranty, int $id, string $condition, string $product_name, int $state, float $price_without_tax, string $product_extra_info, float $price_with_tax, int $pid, string $manufacturers_code) {
        $this->tax = $tax;
        $this->warranty = $warranty;
        $this->id = $id;
        $this->condition = $condition;
        $this->product_name = $product_name;
        $this->state = $state;
        $this->price_without_tax = $price_without_tax;
        $this->product_extra_info = $product_extra_info;
        $this->price_with_tax = $price_with_tax;
        $this->pid = $pid;
        $this->manufacturers_code = $manufacturers_code;
    }
    public function getTax(): int {
        return $this->tax;
    }

    public function getWarranty(): int {
        return $this->warranty;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getCondition(): string {
        return $this->condition;
    }

    public function getProduct_name(): string {
        return $this->product_name;
    }

    public function getState(): int {
        return $this->state;
    }

    public function getPrice_without_tax(): float {
        return $this->price_without_tax;
    }

    public function getProduct_extra_info(): string {
        return $this->product_extra_info;
    }

    public function getPrice_with_tax(): float {
        return $this->price_with_tax;
    }

    public function getPid(): int {
        return $this->pid;
    }

    public function getManufacturers_code(): string {
        return $this->manufacturers_code;
    }

    public function setTax(int $tax): void {
        $this->tax = $tax;
    }

    public function setWarranty(int $warranty): void {
        $this->warranty = $warranty;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setCondition(string $condition): void {
        $this->condition = $condition;
    }

    public function setProduct_name(string $product_name): void {
        $this->product_name = $product_name;
    }

    public function setState(int $state): void {
        $this->state = $state;
    }

    public function setPrice_without_tax(float $price_without_tax): void {
        $this->price_without_tax = $price_without_tax;
    }

    public function setProduct_extra_info(string $product_extra_info): void {
        $this->product_extra_info = $product_extra_info;
    }

    public function setPrice_with_tax(float $price_with_tax): void {
        $this->price_with_tax = $price_with_tax;
    }

    public function setPid(int $pid): void {
        $this->pid = $pid;
    }

    public function setManufacturers_code(string $manufacturers_code): void {
        $this->manufacturers_code = $manufacturers_code;
    }


}
