<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use App\Exceptions\NotFound\AccountNotFoundException;
use App\Exceptions\StructureViolation\CannotDeleteOwnAccountException;
use App\Service\EntityService\AccountService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("admin/users/{mail}/delete", name="gui__admin_user_delete")
     */
    public function delete($mail): RedirectResponse
    {
        // TODO enhance this
        try {
            $this->accountService->delete($mail, $this->getUser());
        } catch (AccountNotFoundException $e) {
        } catch (CannotDeleteOwnAccountException $e) {
        } catch (ORMException $e) {
        } finally {
            return $this->redirectToRoute('gui__admin_users');
        }
    }
}
