<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AbstractLifecycleEntity
 *
 *
 * @ORM\HasLifecycleCallbacks()
 * @package App\Entity
 */
class AbstractLifecycleEntity
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
}
