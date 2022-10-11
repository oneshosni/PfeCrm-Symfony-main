<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Image;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $Prix;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="non")
     * @ORM\JoinColumn(nullable=false)
     */
    private $CreatedBy;

    /**
     * @ORM\OneToMany(targetEntity=DemandeInfo::class, mappedBy="Prduit")
     */
    private $demandeInfos;

    public function __construct()
    {
        $this->demandeInfos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(?string $Image): self
    {
        $this->Image = $Image;
        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->Prix;
    }

    public function setPrix(string $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->CreatedBy;
    }

    public function setCreatedBy(?User $CreatedBy): self
    {
        $this->CreatedBy = $CreatedBy;

        return $this;
    }

    /**
     * @return Collection<int, DemandeInfo>
     */
    public function getDemandeInfos(): Collection
    {
        return $this->demandeInfos;
    }

    public function addDemandeInfo(DemandeInfo $demandeInfo): self
    {
        if (!$this->demandeInfos->contains($demandeInfo)) {
            $this->demandeInfos[] = $demandeInfo;
            $demandeInfo->setProduit($this);
        }

        return $this;
    }

    public function removeDemandeInfo(DemandeInfo $demandeInfo): self
    {
        if ($this->demandeInfos->removeElement($demandeInfo)) {
            // set the owning side to null (unless already changed)
            if ($demandeInfo->getProduit() === $this) {
                $demandeInfo->setProduit(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->getName();
    }
}
