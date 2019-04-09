<?php declare(strict_types=1);

namespace App\Entity\Abstracts;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use FOS\UserBundle\Model\User as BaseFosUser;

/**
 * Abstract class with automatic create and last update date
 *
 * @ORM\HasLifecycleCallbacks()
 * @package App\Entity
 */
abstract class AbstractLifecycleAccountEntity extends BaseFosUser
{
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\PrePersist()
     * @throws Exception
     */
    public function prePersist(): void
    {
        $this->createdAt = new DateTime('now');
    }

    /**
     * @return DateTimeInterface|null the date when entity were createe
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }
}
