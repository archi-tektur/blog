<?php declare(strict_types=1);

namespace App\DTO\Abstracts;

/**
 * Functions required by confirm screen template
 *
 * @package App\DTO\Abstracts
 */
interface ConfirmScreenConfigInterface
{
    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return string
     */
    public function getContent(): string;

    /**
     * @return string
     */
    public function getOkButtonText(): string;

    /**
     * @return string
     */
    public function getOkButtonLink(): string;

    /**
     * @return string
     */
    public function getCancelButtonText(): string;

    /**
     * @return string
     */
    public function getCancelButtonLink(): string;
}
