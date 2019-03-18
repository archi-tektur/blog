<?php declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Abstract class with automatic create and last update date
 *
 * @ORM\HasLifecycleCallbacks()
 * @package App\Entity
 */
abstract class AbstractLifecycleEntity
{
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @ORM\PrePersist()
     * @throws \Exception
     */
    public function prePersist(): void
    {
        $this->createdAt = new DateTime('now');
    }

    /**
     * @ORM\PreUpdate()
     * @throws \Exception
     */
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTime('now');
    }

    /**
     * @return DateTimeInterface|null the date when entity were createe
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeInterface|null the date when entity was last time edited
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }
}
