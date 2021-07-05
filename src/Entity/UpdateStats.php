<?php

namespace App\Entity;

use App\Repository\UpdateStatsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UpdateStatsRepository::class)
 */
class UpdateStats
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalItems;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $sum;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $deleted;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $new;
    
    
    public function __construct() {
        
    }
    public function getDeleted(): ?int {
        return $this->deleted;
    }

    public function setDeleted(int $deleted): self {
        $this->deleted = $deleted;
        return $this;
    }
    
    public function getNew(): ?int {
        return $this->new;
    }

    public function setNew(int $new): self {
        $this->new = $new;
        return $this;
    }
    public function getSum(): ?float {
        return $this->sum;
    }

    public function setSum(float $sum): self {
        $this->sum = $sum;
        return $this;
    }

        public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getTotalItems(): ?int
    {
        return $this->totalItems;
    }

    public function setTotalItems(int $totalItems): self
    {
        $this->totalItems = $totalItems;

        return $this;
    }
}
