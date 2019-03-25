<?php declare(strict_types=1);

namespace App\Service\UploaderService;

use App\Entity\Account;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AccountProfilePictureUploader implements UploaderInterface
{
    private $targetDirectory;

    /** @inheritdoc */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /** @inheritdoc */
    public function upload(UploadedFile $file, $account): ?string
    {
        /** @var Account $account */
        $fileName = getenv('ACCOUNT_IMG_PREFIX') . $account->getEmail() . '.' . $file->guessExtension();
        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {

        }
        return $fileName;
    }

    /** @inheritdoc */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
