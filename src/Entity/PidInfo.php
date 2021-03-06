<?php

namespace App\Entity;

use App\Repository\PidInfoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PidInfoRepository::class)
 */
class PidInfo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $pid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $width;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $depth;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $volume;
    
    public function __construct($pid, $width, $depth, $weight, $height, $volume) {
        $this->pid = $pid;
        $this->width = $width;
        $this->depth = $depth;
        $this->weight = $weight;
        $this->height = $height;
        $this->volume = $volume;
    }

        public function getPid(): ?int
    {
        return $this->pid;
    }

    public function setPid(int $pid): self
    {
        $this->pid = $pid;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getDepth(): ?int
    {
        return $this->depth;
    }

    public function setDepth(?int $depth): self
    {
        $this->depth = $depth;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getVolume(): ?string
    {
        return $this->volume;
    }

    public function setVolume(?int $volume): self
    {
        $this->volume = $volume;

        return $this;
    }
    public function sizeStr() {
        return (($this->width)/10)." x ".(($this->height)/10)." x ".(($this->depth)/10);
    }
    public function sizeStrSm() {
        return (($this->width)/10)."x".(($this->height)/10)."x".(($this->depth)/10);
    }
}
