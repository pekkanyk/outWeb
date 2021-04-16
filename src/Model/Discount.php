<?php

namespace App\Model;

class Discount
{
    private int $id;
    private string $endAt;
    private string $beginAt;
    private int $discountType;
    private string $name;
    
    public function __construct(int $id, string $endAt, string $beginAt, int $discountType, string $name) {
        $this->id = $id;
        $this->endAt = $endAt;
        $this->beginAt = $beginAt;
        $this->discountType = $discountType;
        $this->name = $name;
    }
    public function getId(): int {
        return $this->id;
    }

    public function getEndAt(): string {
        return $this->endAt;
    }

    public function getBeginAt(): string {
        return $this->beginAt;
    }

    public function getDiscountType(): int {
        return $this->discountType;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setEndAt(string $endAt): void {
        $this->endAt = $endAt;
    }

    public function setBeginAt(string $beginAt): void {
        $this->beginAt = $beginAt;
    }

    public function setDiscountType(int $discountType): void {
        $this->discountType = $discountType;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

}
