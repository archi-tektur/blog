<?php declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Account;
use App\Service\UploaderService\ArticleShowreelUploader as Uploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Listens when Account entity is uploaded
 *
 * @package App\EventListener
 */
class AccountProfilePictureUploadListener
{
    private $uploader;

    public function __construct(Uploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * Ran each time before entity is being inserted to database
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Account) {
            return;
        }

        $this->uploadFile($entity);
    }

    /**
     * Uploads file automatically
     *
     * @param $entity
     */
    private function uploadFile($entity): void
    {
        // upload only works for Product entities
        if (!$entity instanceof Account) {
            return;
        }

        $file = $entity->getProfileImage();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file, $entity);
            $entity->setProfileImage($fileName);
        } elseif ($file instanceof File) {
            // prevents the full file path being saved on updates
            // as the path is set on the postLoad listener
            $entity->setProfileImage($file->getFilename());
        }
    }

    /**
     * Ran each time before entity is being updated in database
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Account) {
            return;
        }

        $this->uploadFile($entity);
    }

    /**
     * Ran each time after entity is loaded to memory, changes file path to file object
     *
     * @see File
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Account) {
            return;
        }

        if ($fileName = $entity->getProfileImage()) {
            $file = new File($this->uploader->getTargetDirectory() . '/' . $fileName);
            $entity->setProfileImage($file);
        }
    }
}
