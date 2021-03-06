<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel\Confirmations;

use App\Controller\Abstracts\ConfirmScreenController;
use App\DTO\ConfirmScreenConfig;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 *
 * @package App\Controller\GUI\AdminPanel
 */
class CategoryConfirmationController extends ConfirmScreenController
{
    /**
     * Confirm screen texts
     */
    private const EDIT_CS_TITLE = 'category.edit.confirm.title';
    private const EDIT_CS_CONTENT = 'category.edit.confirm.content';
    private const EDIT_CS_BACK_TEXT = 'category.edit.confirm.back-text';
    private const EDIT_CS_OK_TEXT = 'category.edit.confirm.ok-text';

    private const DELETE_CS_TITLE = 'category.delete.confirm.title';
    private const DELETE_CS_CONTENT = 'category.delete.confirm.content';
    private const DELETE_CS_BACK_TEXT = 'category.delete.confirm.back-text';
    private const DELETE_CS_OK_TEXT = 'category.delete.confirm.ok-text';

    private const TRANSLATION_DOMAIN = 'confirm-category';

    /**
     * Renders warning screen before removing category
     *
     * @Route("admin/confirm/categories/{name}/edit", name="gui__admin_confirm_categories_edit")
     * @param string $name
     * @return Response
     */
    public function edit(string $name): Response
    {
        // TODO fix okLink
        $cancelLink = $this->generateUrl('gui__admin_categories_index');
        $okLink = $this->generateUrl('gui__admin_categories_delete', ['name' => $name]);

        $config = new ConfirmScreenConfig();
        $config->setTitle(self::EDIT_CS_TITLE)
               ->setContent(self::EDIT_CS_CONTENT)
               ->setOkButtonText(self::EDIT_CS_OK_TEXT)
               ->setOkButtonLink($okLink)
               ->setCancelButtonText(self::EDIT_CS_BACK_TEXT)
               ->setCancelButtonLink($cancelLink)
               ->setTranslationDomain(self::TRANSLATION_DOMAIN)
               ->setTranslatable(true);

        return $this->confirm($config);
    }

    /**
     * Renders warning screen before removing category
     *
     * @Route("admin/confirm/categories/{name}/delete", name="gui__admin_confirm_categories_delete")
     * @param string $name
     * @return Response
     */
    public function delete(string $name): Response
    {
        $cancelLink = $this->generateUrl('gui__admin_categories_index');
        $okLink = $this->generateUrl('gui__admin_categories_delete', ['name' => $name]);

        $config = new ConfirmScreenConfig();
        $config->setTitle(self::DELETE_CS_TITLE)
               ->setContent(self::DELETE_CS_CONTENT)
               ->setOkButtonText(self::DELETE_CS_OK_TEXT)
               ->setOkButtonLink($okLink)
               ->setCancelButtonText(self::DELETE_CS_BACK_TEXT)
               ->setCancelButtonLink($cancelLink)
               ->setTranslationDomain(self::TRANSLATION_DOMAIN)
               ->setTranslatable(true);

        return $this->confirm($config);
    }
}
