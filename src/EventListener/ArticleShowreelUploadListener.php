<?php declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Article;
use App\Service\UploaderService\ArticleShowreelUploader as Uploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleShowreelUploadListener
{
    private $uploader;

    public function __construct(Uploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

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
        if (!$entity instanceof Article) {
            return;
        }

        $file = $entity->getShowreelImage();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file, $entity);
            $entity->setShowreelImage($fileName);
        } elseif ($file instanceof File) {
            // prevents the full file path being saved on updates
            // as the path is set on the postLoad listener
            $entity->setShowreelImage($file->getFilename());
        }
    }
}
