<?php

namespace App\Entity;

use App\Repository\DemandeInfoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DemandeInfoRepository::class)
 */
class DemandeInfo
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
    private $Description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Type;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="demandeInfos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $CreatedBy;

    /**
     * @ORM\OneToMany(targetEntity=Categorie::class, mappedBy="demandeInfo")
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="demandeInfos")
     */
    private $Produit;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

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

    public function getProduit(): ?Produit
    {
        return $this->Produit;
    }

    public function setProduit(?Produit $Produit): self
    {
        $this->Produit = $Produit;

        return $this;
    }


}
