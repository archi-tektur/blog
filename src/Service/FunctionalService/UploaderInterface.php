<?php declare(strict_types=1);

namespace App\Service\FunctionalService;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploaderInterface
{
    /**
     *
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory);

    /**
     * Function that uploads file to server
     *
     * @param UploadedFile $file
     * @param string       $fileName
     * @return string
     */
    public function upload(UploadedFile $file, string $fileName): string;

    /**
     * @return string
     */
    public function getTargetDirectory(): string;
}
