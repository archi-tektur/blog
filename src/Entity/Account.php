<?php

namespace App\Entity;

use App\Tools\RandomStringGenerator;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 * @UniqueEntity(fields={"email"})
 */
class Account extends AbstractLifecycleEntity implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @Assert\File(
     *     maxSize="2048k",
     *     mimeTypes={"image/jpeg", "image/bmp", "image/png"},
     *     mimeTypesMessage="error.mimeTypesMessage",
     *     maxSizeMessage="error.maxSiseMessage",
     * )
     */
    private $profileImage;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Article", mappedBy="authors")
     */
    private $articles;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $apiPartialKey;

    /**
     * @ORM\Column(type="datetime")
     */
    private $agreedTermsAt;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
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
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getProfileImage()
    {
        return $this->profileImage;
    }

    public function setProfileImage($profileImage): self
    {
        $this->profileImage = $profileImage;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addAuthor($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            $article->removeAuthor($this);
        }

        return $this;
    }

    public function getApiPartialKey(): ?string
    {
        return $this->apiPartialKey;
    }

    public function setApiPartialKey(string $apiPartialKey): self
    {
        $this->apiPartialKey = $apiPartialKey;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist()
     * @throws Exception
     */
    public function setApiKey()
    {
        $this->apiPartialKey = RandomStringGenerator::generate(64);
    }

    /**
     * @return string user name and surname
     */
    public function __toString(): string
    {
        return $this->name . ' ' . $this->surname;
    }

    public function getAgreedTermsAt(): ?DateTimeInterface
    {
        return $this->agreedTermsAt;
    }

    public function agreeToTerms()
    {
        try {
            $this->agreedTermsAt = new DateTime();
        } catch (Exception $e) {
        }
    }
}
