<?php declare(strict_types=1);

namespace App\EventSubscriber\Doctrine;

use App\Entity\Article;
use App\Service\UploaderService\ArticleShowreelUploader as Uploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events as DoctrineEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Listens when Article entity is uploaded
 *
 * @package App\EventSubscriber
 */
class ArticleActionsSubscriber implements EventSubscriberInterface
{
    /** @var Uploader */
    private $uploader;

    public function __construct(Uploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /** @inheritDoc */
    public static function getSubscribedEvents(): array
    {
        return [
            DoctrineEvents::prePersist => 'prePersist',
            DoctrineEvents::preUpdate  => 'preUpdate',
            DoctrineEvents::postLoad   => 'postLoad',
            DoctrineEvents::postRemove => 'postRemove',
        ];
    }

    /**
     * Ran each time before entity is being inserted to database
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Article) {
            return;
        }

        $this->uploadFile($entity);
    }

    /**
     * Ran each time before entity is being updated in database
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Article) {
            return;
        }

        $this->uploadFile($entity);
    }

    /**
     * Ran each time after entity is loaded to memory, changes file path to file object
     *
     * @param LifecycleEventArgs $args
     * @see File
     */
    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        dd('WORKING');

        if (!$entity instanceof Article) {
            return;
        }

        if ($fileName = $entity->getShowreelImage()) {
            $file = new File($this->uploader->getTargetDirectory() . '/' . $fileName);
            $entity->setShowreelImage($file);
        }
    }

    /**
     * Ran each time an article is deleted, delete related image then
     *
     * @param LifecycleEventArgs $args
     * @see File
     */
    public function postRemove(LifecycleEventArgs $args): void
    {
        $article = $args->getEntity();
        // upload only works for Article entities
        if (!$article instanceof Article) {
            return;
        }

        // if showreel image exists, delete it
        if ($article instanceof UploadedFile || $article->getShowreelImage() instanceof File) {
            $filepath = $article->getShowreelImage()->getRealPath();
            unlink($filepath);
        }
    }

    /**
     * Uploads file automatically
     *
     * @param $entity
     */
    private function uploadFile($entity): void
    {
        // upload only works for Article entities
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
