<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"})
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface, EquatableInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank( message="Ne doit pas être vide")
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Ne doit pas être vide")
     */
    private $nomComplet;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Ne doit pas être vide")
     * @Assert\Email(message="Email invalide")
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $valid;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted;

    /**
     * @ORM\Column(type="string", length=255))
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=BlogPost::class, mappedBy="author")
     */
    private $blogPosts;

    /**
     * @ORM\OneToMany(targetEntity=BlogPost::class, mappedBy="creator")
     */
    private $blogPostsCreated;

    /**
     * @ORM\Column(type="boolean")
     */
    private $admin;

    /**
     * @ORM\OneToMany(targetEntity=Historique::class, mappedBy="user")
     */
    private $historiques;

    /**
     * @ORM\OneToMany(targetEntity=Reclamation::class, mappedBy="createdBy", orphanRemoval=true)
     */
    private $reclamations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $PhoneNumber;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * use Gedmo\Mapping\Annotation as Gedmo;
     */
    private $Birthday;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Address;

    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="CreatedBy", orphanRemoval=true)
     */
    private $non;

    /**
     * @ORM\OneToMany(targetEntity=DemandeInfo::class, mappedBy="CreatedBy", orphanRemoval=true)
     */
    private $demandeInfos;


    public function __construct()
    {
        $this->blogPosts = new ArrayCollection();
        $this->blogPostsCreated = new ArrayCollection();
        $this->historiques = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
        $this->non = new ArrayCollection();
        $this->demandeInfos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername($username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet( $nomComplet): self
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail( $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAvatarUrl($size){
        return "https://api.adorable.io/avatars/$size/".$this->username;
    }


    function getColorCode() {
        $code = dechex(crc32($this->getUsername()));
        $code = substr($code, 0, 6);
        return "#".$code;
    }

    /**
     * @Assert\Callback
     */

    public function validate(ExecutionContextInterface $context, $payload)
    {
        /*if (strlen($this->password)< 3){
            $context->buildViolation('Mot de passe trop court')
                ->atPath('justpassword')
                ->addViolation();
        }*/
    }

    /**
     * @return Collection|BlogPost[]
     */
    public function getBlogPosts(): Collection
    {
        return $this->blogPosts;
    }

    public function addBlogPost(BlogPost $blogPost): self
    {
        if (!$this->blogPosts->contains($blogPost)) {
            $this->blogPosts[] = $blogPost;
            $blogPost->setAuthor($this);
        }

        return $this;
    }

    public function removeBlogPost(BlogPost $blogPost): self
    {
        if ($this->blogPosts->contains($blogPost)) {
            $this->blogPosts->removeElement($blogPost);
            // set the owning side to null (unless already changed)
            if ($blogPost->getAuthor() === $this) {
                $blogPost->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BlogPost[]
     */
    public function getBlogPostsCreated(): Collection
    {
        return $this->blogPostsCreated;
    }

    public function addBlogPostsCreated(BlogPost $blogPostsCreated): self
    {
        if (!$this->blogPostsCreated->contains($blogPostsCreated)) {
            $this->blogPostsCreated[] = $blogPostsCreated;
            $blogPostsCreated->setCreator($this);
        }

        return $this;
    }

    public function removeBlogPostsCreated(BlogPost $blogPostsCreated): self
    {
        if ($this->blogPostsCreated->contains($blogPostsCreated)) {
            $this->blogPostsCreated->removeElement($blogPostsCreated);
            // set the owning side to null (unless already changed)
            if ($blogPostsCreated->getCreator() === $this) {
                $blogPostsCreated->setCreator(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return "$this->nomComplet ($this->id)";
    }

    public function isAdmin(): ?bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * @return Collection|Historique[]
     */
    public function getHistoriques(): Collection
    {
        return $this->historiques;
    }

    public function addHistorique(Historique $historique): self
    {
        if (!$this->historiques->contains($historique)) {
            $this->historiques[] = $historique;
            $historique->setUser($this);
        }

        return $this;
    }

    public function removeHistorique(Historique $historique): self
    {
        if ($this->historiques->contains($historique)) {
            $this->historiques->removeElement($historique);
            // set the owning side to null (unless already changed)
            if ($historique->getUser() === $this) {
                $historique->setUser(null);
            }
        }

        return $this;
    }


    public function isEqualTo(UserInterface $user)
    {
        if ($user instanceof User)
        return $this->isValid() && !$this->isDeleted() && $this->getPassword() == $user->getPassword() && $this->getUsername() == $user->getUsername()
            && $this->getEmail() == $user->getEmail() ;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations[] = $reclamation;
            $reclamation->setCreatedBy($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getCreatedBy() === $this) {
                $reclamation->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->PhoneNumber;
    }

    public function setPhoneNumber(?string $PhoneNumber): self
    {
        $this->PhoneNumber = $PhoneNumber;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->Birthday;
    }

    public function setBirthday(?\DateTimeInterface $Birthday): self
    {
        $this->Birthday = $Birthday;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): self
    {
        $this->Address = $Address;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getNon(): Collection
    {
        return $this->non;
    }

    public function addNon(Produit $non): self
    {
        if (!$this->non->contains($non)) {
            $this->non[] = $non;
            $non->setCreatedBy($this);
        }

        return $this;
    }

    public function removeNon(Produit $non): self
    {
        if ($this->non->removeElement($non)) {
            // set the owning side to null (unless already changed)
            if ($non->getCreatedBy() === $this) {
                $non->setCreatedBy(null);
            }
        }

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
            $demandeInfo->setCreatedBy($this);
        }

        return $this;
    }

    public function removeDemandeInfo(DemandeInfo $demandeInfo): self
    {
        if ($this->demandeInfos->removeElement($demandeInfo)) {
            // set the owning side to null (unless already changed)
            if ($demandeInfo->getCreatedBy() === $this) {
                $demandeInfo->setCreatedBy(null);
            }
        }

        return $this;
    }
}
