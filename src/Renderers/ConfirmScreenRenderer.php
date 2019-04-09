<?php declare(strict_types=1);

namespace App\Renderers;

use App\DTO\Abstracts\ConfirmScreenConfigInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Facade for syntax sugar on rendering confirm screens
 *
 * @package App\Controller\GUI
 */
class ConfirmScreenRenderer extends AbstractController
{
    /** @var string */
    private $templatePath = 'admin/confirmation/confirm.html.twig';

    /** @var string */
    private $twigKey = 'config';

    /**
     * Sets fields in renderer, useable when user uses it's own template
     *
     * @param string $templatePath
     * @param string $twigKey
     */
    public function setup(
        string $templatePath = 'admin/confirmation/confirm.html.twig',
        string $twigKey = 'config'
    ): void {
        $this->twigKey = $twigKey;
        $this->templatePath = $templatePath;
    }

    /**
     * Renders valid confirm screen
     *
     * @param ConfirmScreenConfigInterface $confirmScreenConfig
     * @return Response
     */
    public function renderConfirmScreen(ConfirmScreenConfigInterface $confirmScreenConfig): Response
    {
        return $this->render($this->templatePath, [
            $this->twigKey => $confirmScreenConfig,
        ]);
    }
}
