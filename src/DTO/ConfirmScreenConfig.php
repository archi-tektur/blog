<?php declare(strict_types=1);

namespace App\DTO;

use App\DTO\Abstracts\ConfirmScreenConfigInterface;

/**
 * Object holding config screen details in detailed way
 *
 * @package App\DTO
 */
class ConfirmScreenConfig implements ConfirmScreenConfigInterface
{
    /** @var string */
    private $title = 'confirm.title';

    /** @var string */
    private $content = 'confirm.content';

    /** @var string */
    private $okButtonText = 'confirm.ok';

    /** @var string */
    private $okButtonLink = '/';

    /** @var string */
    private $cancelButtonText = 'confirm.back';

    /** @var string */
    private $cancelButtonLink = '/';

    /** @var bool */
    private $translatable = false;

    /** @var string */
    private $translationDomain = 'confirm-screen';

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return ConfirmScreenConfig
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     * @return ConfirmScreenConfig
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getOkButtonText(): string
    {
        return $this->okButtonText;
    }

    /**
     * @param string $okButtonText
     * @return ConfirmScreenConfig
     */
    public function setOkButtonText(string $okButtonText): self
    {
        $this->okButtonText = $okButtonText;
        return $this;
    }

    /**
     * @return string
     */
    public function getOkButtonLink(): string
    {
        return $this->okButtonLink;
    }

    /**
     * @param string $okButtonLink
     * @return ConfirmScreenConfig
     */
    public function setOkButtonLink(string $okButtonLink): self
    {
        $this->okButtonLink = $okButtonLink;
        return $this;
    }

    /**
     * @return string
     */
    public function getCancelButtonText(): string
    {
        return $this->cancelButtonText;
    }

    /**
     * @param string $cancelButtonText
     * @return ConfirmScreenConfig
     */
    public function setCancelButtonText(string $cancelButtonText): self
    {
        $this->cancelButtonText = $cancelButtonText;
        return $this;
    }

    /**
     * @return string
     */
    public function getCancelButtonLink(): string
    {
        return $this->cancelButtonLink;
    }

    /**
     * @param string $cancelButtonLink
     * @return ConfirmScreenConfig
     */
    public function setCancelButtonLink(string $cancelButtonLink): self
    {
        $this->cancelButtonLink = $cancelButtonLink;
        return $this;
    }

    /**
     * @return string
     */
    public function getTranslationDomain(): string
    {
        return $this->translationDomain;
    }

    /**
     * @param string $translationDomain
     * @return ConfirmScreenConfig
     */
    public function setTranslationDomain(string $translationDomain): self
    {
        $this->translationDomain = $translationDomain;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTranslatable(): bool
    {
        return $this->translatable;
    }

    /**
     * @param bool $translatable
     * @return ConfirmScreenConfig
     */
    public function setTranslatable(bool $translatable): self
    {
        $this->translatable = $translatable;
        return $this;
    }
}
