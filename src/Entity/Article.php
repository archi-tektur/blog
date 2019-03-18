<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @UniqueEntity(fields={"slug"})
 */
class Article extends AbstractLifecycleEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Account", inversedBy="articles")
     */
    private $authors;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="article", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="articles")
     */
    private $categories;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $showreelImage;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Account $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Account $author): self
    {
        if ($this->authors->contains($author)) {
            $this->authors->removeElement($author);
        }

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
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->title;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function generateSlug(): void
    {
        if ($this->slug === null) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
    }

    public function getShowreelImage()
    {
        return $this->showreelImage;
    }

    public function setShowreelImage($showreelImage): self
    {
        $this->showreelImage = $showreelImage;

        return $this;
    }
}
