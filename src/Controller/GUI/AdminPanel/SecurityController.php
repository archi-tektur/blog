<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use App\Form\RegisterFormType;
use App\Service\EntityService\AccountService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SecurityController
 *
 * @package App\Controller\GUI\AdminPanel
 */
class SecurityController extends AbstractController
{
    /**
     * @var EntityManager
     */
    protected $entityManager;
    /**
     * @var AccountService
     */
    protected $accountService;

    /**
     * SecurityController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param AccountService         $accountService
     */
    public function __construct(EntityManagerInterface $entityManager, AccountService $accountService)
    {
        $this->entityManager = $entityManager;
        $this->accountService = $accountService;
    }

    public function login()
    {
    }

    public function logoff()
    {
    }

    /**
     * @Route("/admin/register/", name="gui__admin_register")
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        $form = $this->createForm(RegisterFormType::class);
        $form->handleRequest($request);

        return $this->render('admin/security/register.html.twig', ['form' => $form->createView()]);
    }

    public function details()
    {
    }
}
