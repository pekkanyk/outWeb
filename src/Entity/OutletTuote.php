<?php

namespace App\Entity;

use App\Repository\OutletTuoteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OutletTuoteRepository::class)
 */
class OutletTuote
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $outId;

    /**
     * @ORM\Column(type="integer")
     */
    private $pid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $outPrice;

    /**
     * @ORM\Column(type="float")
     */
    private $norPrice;

    /**
     * @ORM\Column(type="date")
     */
    private $priceUpdatedDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $updated;

    /**
     * @ORM\Column(type="boolean")
     */
    private $poistotuote;

    /**
     * @ORM\Column(type="boolean")
     */
    private $dumppituote;

    /**
     * @ORM\Column(type="float")
     */
    private $alennus;

    /**
     * @ORM\Column(type="integer")
     */
    private $warranty;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $condition;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $deleted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $kampanja;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $kamploppuu;

    /**
     * @ORM\Column(type="date")
     */
    private $firstSeen;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $varastossa;

    /**
     * @ORM\Column(type="boolean")
     */
    private $onVarasto;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $koko;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $pidLuotu;

    public function getOutId(): ?int
    {
        return $this->outId;
    }

    public function setOutId(int $outId): self
    {
        $this->outId = $outId;

        return $this;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOutPrice(): ?float
    {
        return $this->outPrice;
    }

    public function setOutPrice(float $outPrice): self
    {
        $this->outPrice = $outPrice;

        return $this;
    }

    public function getNorPrice(): ?float
    {
        return $this->norPrice;
    }

    public function setNorPrice(float $norPrice): self
    {
        $this->norPrice = $norPrice;

        return $this;
    }

    public function getPriceUpdatedDate(): ?\DateTimeInterface
    {
        return $this->priceUpdatedDate;
    }

    public function setPriceUpdatedDate(?\DateTimeInterface $priceUpdatedDate): self
    {
        $this->priceUpdatedDate = $priceUpdatedDate;

        return $this;
    }

    public function getUpdated(): ?bool
    {
        return $this->updated;
    }

    public function setUpdated(?bool $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getPoistotuote(): ?bool
    {
        return $this->poistotuote;
    }

    public function setPoistotuote(?bool $poistotuote): self
    {
        $this->poistotuote = $poistotuote;

        return $this;
    }

    public function getDumppituote(): ?bool
    {
        return $this->dumppituote;
    }

    public function setDumppituote(?bool $dumppituote): self
    {
        $this->dumppituote = $dumppituote;

        return $this;
    }

    public function getAlennus(): ?float
    {
        return $this->alennus;
    }

    public function setAlennus(float $alennus): self
    {
        $this->alennus = $alennus;

        return $this;
    }

    public function getWarranty(): ?int
    {
        return $this->warranty;
    }

    public function setWarranty(int $warranty): self
    {
        $this->warranty = $warranty;

        return $this;
    }

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public function setCondition(string $condition): self
    {
        $this->condition = $condition;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?\DateTimeInterface $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getKampanja(): ?bool
    {
        return $this->kampanja;
    }

    public function setKampanja(bool $kampanja): self
    {
        $this->kampanja = $kampanja;

        return $this;
    }

    public function getKamploppuu(): ?\DateTimeInterface
    {
        return $this->kamploppuu;
    }

    public function setKamploppuu(?\DateTimeInterface $kamploppuu): self
    {
        $this->kamploppuu = $kamploppuu;

        return $this;
    }

    public function getFirstSeen(): ?\DateTimeInterface
    {
        return $this->firstSeen;
    }

    public function setFirstSeen(\DateTimeInterface $firstSeen): self
    {
        $this->firstSeen = $firstSeen;

        return $this;
    }

    public function getVarastossa(): ?int
    {
        return $this->varastossa;
    }

    public function setVarastossa(?int $varastossa): self
    {
        $this->varastossa = $varastossa;

        return $this;
    }

    public function getOnVarasto(): ?bool
    {
        return $this->onVarasto;
    }

    public function setOnVarasto(?bool $onVarasto): self
    {
        $this->onVarasto = $onVarasto;

        return $this;
    }

    public function getKoko(): ?string
    {
        return $this->koko;
    }

    public function setKoko(?string $koko): self
    {
        $this->koko = $koko;

        return $this;
    }

    public function getPidLuotu(): ?\DateTimeInterface
    {
        return $this->pidLuotu;
    }

    public function setPidLuotu(?\DateTimeInterface $pidLuotu): self
    {
        $this->pidLuotu = $pidLuotu;

        return $this;
    }
}
