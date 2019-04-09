<?php

namespace App\Entity;

use App\Entity\Abstracts\AbstractLifecycleAccountEntity;
use App\Tools\RandomStringGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks()
 */
class Account extends AbstractLifecycleAccountEntity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $profileImage;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Article", mappedBy="authors")
     */
    private $articles;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $apiPartialKey;

    public function __construct()
    {
        parent::__construct();
        $this->articles = new ArrayCollection();
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

    public function getProfileImage(): ?string
    {
        return $this->profileImage;
    }

    public function setProfileImage(?string $profileImage): self
    {
        $this->profileImage = $profileImage;

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

    /**
     * @ORM\PrePersist()
     * @throws Exception
     */
    public function setApiKey(): void
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
}
