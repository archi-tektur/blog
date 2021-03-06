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
class AccountConfirmationController extends ConfirmScreenController
{
    /**
     * Confirm screen texts
     */
    private const EDIT_CS_TITLE = 'account.edit.confirm.title';
    private const EDIT_CS_CONTENT = 'account.edit.confirm.content';
    private const EDIT_CS_BACK_TEXT = 'account.edit.confirm.back-text';
    private const EDIT_CS_OK_TEXT = 'account.edit.confirm.ok-text';

    private const DELETE_CS_TITLE = 'account.delete.confirm.title';
    private const DELETE_CS_CONTENT = 'account.delete.confirm.content';
    private const DELETE_CS_BACK_TEXT = 'account.delete.confirm.back-text';
    private const DELETE_CS_OK_TEXT = 'account.delete.confirm.ok-text';

    private const TRANSLATION_DOMAIN = 'confirm-account';

    public function __construct()
    {
    }

    /**
     * Renders warning screen before removing category
     *
     * @Route("admin/confirm/categories/{name}/edit", name="gui__admin_confirm_categories_edit")
     * @param string $name
     * @return void
     */
    public function edit(string $name): void
    {
        // TODO all
    }

    /**
     * Renders warning screen before removing an account
     *
     * @Route("admin/confirm/categories/{login}/delete", name="gui__admin_confirm_user_delete")
     * @param string $login
     * @return Response
     */
    public function delete(string $login): Response
    {
        $cancelLink = $this->generateUrl('gui__admin_users');
        $okLink = $this->generateUrl('gui__admin_user_delete', ['login' => $login]);

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
