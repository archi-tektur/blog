<?php declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Entity\Account;
use App\Service\UploaderService\AccountProfilePictureUploader as Uploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Listens when Account entity is uploaded
 *
 * @package App\EventListener
 */
class AccountListener
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

        /** @var UploadedFile $file */
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
     * Ran each time user is removed
     *
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Account) {
            return;
        }

        // delete profile picture
        $filepath = $this->uploader->getTargetDirectory() . DIRECTORY_SEPARATOR . $entity->getProfileImage();
        if (is_file($filepath)) {
            unlink($filepath);
        }
    }
}
