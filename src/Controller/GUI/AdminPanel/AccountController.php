<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use App\Exceptions\NotFound\AccountNotFoundException;
use App\Service\EntityService\AccountService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * Class AccountController
 *
 * @package App\Controller\GUI\AdminPanel
 */
class AccountController extends AbstractController
{
    /**
     * @var AccountService
     */
    protected $accountService;
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * AccountController constructor.
     *
     * @param AccountService         $accountService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(AccountService $accountService, EntityManagerInterface $entityManager)
    {
        $this->accountService = $accountService;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/users", name="gui__admin_users")
     * @return Response
     */
    public function index(): Response
    {
        $users = $this->accountService->getAll();
        return $this->render('admin/panels/users.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("admin/users/{login}/delete", name="gui__admin_user_delete")
     * @param $login
     * @return RedirectResponse
     */
    public function delete(string $login): RedirectResponse
    {
        // TODO enhance this
        try {
            $this->accountService->delete($login, $this->getUser());
        } catch (Throwable $e) {
            dd($e->getMessage());
        } finally {
            return $this->redirectToRoute('gui__admin_users');
        }
    }

    /**
     * Regenerates api key on user
     *
     * @param string $login
     * @return RedirectResponse
     * @Route("admin/users/{login}/regenerate-api-key", name="gui__admin_user_regenerate")
     * @throws Exception
     */
    public function regenerate(string $login): RedirectResponse
    {
        try {
            $this->accountService->regenerateApiKey($login);
        } catch (AccountNotFoundException $e) {
        } catch (ORMException $e) {
        } catch (Exception $e) {
        } finally {
            return $this->redirectToRoute('gui__admin_users');
        }
    }
}
