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
     * @ORM\Column(type="string", length=255)
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

    public function getId(): ?string
    {
        return $this->id;
    }
    
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
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
    
    public function printName(): ?string
    {
        $k = "0".$this->kaytava;
        if ($this->vali<=9){
        $v = "0".$this->vali;
        }
        else {
            $v = $this->vali;
        }
        return "3A".$k."-".$v."-".$this->taso.$this->reuna;
        
    }
/* 
 * original function   
    public function vari():?string
    {
        $vari = "#D3D3D3";
        if ($this->getUsage() ==0) {$vari = "#4CAF50";}
        else if ($this->getUsage() ==25) {$vari = "#90EE90";}
        else if ($this->getUsage() ==50) {$vari = "#FFD700";}
        else if ($this->getUsage() ==75) {$vari = "#FFA500";}
        else if ($this->getUsage() ==100) {$vari = "#FF4500";}
        else if ($this->getUsage() ==-1) {$vari = "#696969";}
        else if ($this->getUsage() ==200) {$vari = "#FFE4E1";}
        return $vari;
    }
 */  
    public function vari():?string
    {
        $vari = "#D3D3D3";
        if ($this->getUsage() ==200) {$vari = "#FFE4E1";}
        else if ($this->getUsage() ==-1) {$vari = "#696969";}
        else {
            $da = $this->daysEdited();
            if ($da == "-") {$vari = "#FF4500";}
            else {
                if (intval($da)>90) {$vari = "#FF4500";}
                else if (intval($da)>60) {$vari = "#FFA500";}
                else if (intval($da)>30) {$vari = "#FFD700";}
                else if (intval($da)>14) {$vari = "#90EE90";}
                else {$vari = "#4CAF50";}
            }
            
        }
        
        return $vari;
    }
    public function daysEdited(){
        if ($this->usable && $this->updated != null){
        $today = date_create("today");
        //$today->setTime(00,00,00);
        $da = $this->updated->diff($today)->format('%a');
        return $da;
        }
        else{
            return "-";
        }
    }

}
