<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use App\Service\EntityService\AccountService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        return $this->render('admin/panels/users.html.twig');
    }
}
