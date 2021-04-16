<?php

namespace App\Model;

class Product
{
    private boolean $active;
    private string $createdAt;
    private Price $price;
    private int $visible;
    private boolean $isFireSale;
    private string $warranty;
    private boolean $vak;
    private string $productId;
    private int $pid;
    private string $condition;
    private Package $package;
    private Availability $availability;
    private CustomerReturnsInfo $customerReturnsInfo;

    public function __construct(boolean $active, string $createdAt, Price $price, int $visible, boolean $isFireSale, string $warranty, boolean $vak, string $productId, int $pid, string $condition, Package $package, Availability $availability, CustomerReturnsInfo $customerReturnsInfo) {
        $this->active = $active;
        $this->createdAt = $createdAt;
        $this->price = $price;
        $this->visible = $visible;
        $this->isFireSale = $isFireSale;
        $this->warranty = $warranty;
        $this->vak = $vak;
        $this->productId = $productId;
        $this->pid = $pid;
        $this->condition = $condition;
        $this->package = $package;
        $this->availability = $availability;
        $this->customerReturnsInfo = $customerReturnsInfo;
    }
    public function getActive(): boolean {
        return $this->active;
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    public function getPrice(): Price {
        return $this->price;
    }

    public function getVisible(): int {
        return $this->visible;
    }

    public function getIsFireSale(): boolean {
        return $this->isFireSale;
    }

    public function getWarranty(): string {
        return $this->warranty;
    }

    public function getVak(): boolean {
        return $this->vak;
    }

    public function getProductId(): string {
        return $this->productId;
    }

    public function getPid(): int {
        return $this->pid;
    }

    public function getCondition(): string {
        return $this->condition;
    }

    public function getPackage(): Package {
        return $this->package;
    }

    public function getAvailability(): Availability {
        return $this->availability;
    }

    public function getCustomerReturnsInfo(): CustomerReturnsInfo {
        return $this->customerReturnsInfo;
    }

    public function setActive(boolean $active): void {
        $this->active = $active;
    }

    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }

    public function setPrice(Price $price): void {
        $this->price = $price;
    }

    public function setVisible(int $visible): void {
        $this->visible = $visible;
    }

    public function setIsFireSale(boolean $isFireSale): void {
        $this->isFireSale = $isFireSale;
    }

    public function setWarranty(string $warranty): void {
        $this->warranty = $warranty;
    }

    public function setVak(boolean $vak): void {
        $this->vak = $vak;
    }

    public function setProductId(string $productId): void {
        $this->productId = $productId;
    }

    public function setPid(int $pid): void {
        $this->pid = $pid;
    }

    public function setCondition(string $condition): void {
        $this->condition = $condition;
    }

    public function setPackage(Package $package): void {
        $this->package = $package;
    }

    public function setAvailability(Availability $availability): void {
        $this->availability = $availability;
    }

    public function setCustomerReturnsInfo(CustomerReturnsInfo $customerReturnsInfo): void {
        $this->customerReturnsInfo = $customerReturnsInfo;
    }


}
