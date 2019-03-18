<?php declare(strict_types=1);

namespace App\Service\UploaderService;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleShowreelUploader implements UploaderInterface
{
    private $targetDirectory;

    /** @inheritdoc */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /** @inheritdoc */
    public function upload(UploadedFile $file, $article): ?string
    {
        /** @var Article $article */
        $fileName = getenv('ARTICLE_IMG_PREFIX') . $article->getSlug() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    /** @inheritdoc */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
