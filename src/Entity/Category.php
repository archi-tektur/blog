<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $tags;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags): self
    {
        $this->tags = $tags;

        return $this;
    }
}
