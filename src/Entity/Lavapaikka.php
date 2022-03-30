<?php

namespace App\Entity;

use App\Repository\LavapaikkaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LavapaikkaRepository::class)
 */
class Lavapaikka
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $kaytava;

    /**
     * @ORM\Column(type="integer")
     */
    private $vali;

    /**
     * @ORM\Column(type="integer")
     */
    private $taso;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reuna;

    /**
     * @ORM\Column(type="boolean")
     */
    private $usable;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $usage;

    /**
     * @ORM\Column(type="string", length=511, nullable=true)
     */
    private $sisalto;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $updated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKaytava(): ?int
    {
        return $this->kaytava;
    }

    public function setKaytava(int $kaytava): self
    {
        $this->kaytava = $kaytava;

        return $this;
    }

    public function getVali(): ?int
    {
        return $this->vali;
    }

    public function setVali(int $vali): self
    {
        $this->vali = $vali;

        return $this;
    }

    public function getTaso(): ?int
    {
        return $this->taso;
    }

    public function setTaso(int $taso): self
    {
        $this->taso = $taso;

        return $this;
    }

    public function getReuna(): ?string
    {
        return $this->reuna;
    }

    public function setReuna(string $reuna): self
    {
        $this->reuna = $reuna;

        return $this;
    }

    public function getUsable(): ?bool
    {
        return $this->usable;
    }

    public function setUsable(bool $usable): self
    {
        $this->usable = $usable;

        return $this;
    }

    public function getUsage(): ?int
    {
        return $this->usage;
    }

    public function setUsage(?int $usage): self
    {
        $this->usage = $usage;

        return $this;
    }

    public function getSisalto(): ?string
    {
        return $this->sisalto;
    }

    public function setSisalto(?string $sisalto): self
    {
        $this->sisalto = $sisalto;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

}
