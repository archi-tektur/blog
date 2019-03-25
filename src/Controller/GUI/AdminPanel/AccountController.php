<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use App\Entity\Account;
use App\Exceptions\NotFound\AccountNotFoundException;
use App\Form\EditUserFormType;
use App\Service\EntityService\AccountService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/admin/account/add", name="gui__admin_account_add")
     * @param Request $request
     */
    public function add(Request $request)
    {

    }

    /**
     * @Route("/admin/account/{email}/edit", name="gui__admin_account_edit")
     * @param Request $request
     * @param string  $email
     * @return RedirectResponse
     * @throws AccountNotFoundException
     */
    public function edit(Request $request, string $email): Response
    {
        $account = $this->accountService->get($email);
        $form = $this->createForm(EditUserFormType::class, $account);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Account $account */
            $account = $form->getData();
            $this->entityManager->persist($account);
            $this->entityManager->flush();
            return $this->redirectToRoute('gui__admin_account_list');
        }
        return $this->render('admin/account/account_edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/account/{email}/delete", name="gui__admin_account_delete")
     * @param string $email
     * @return RedirectResponse
     * @throws AccountNotFoundException
     * @throws ORMException
     */
    public function delete(string $email): RedirectResponse
    {
        $this->accountService->delete($email);
        return $this->redirectToRoute('gui__admin_account_list');
    }


    /**
     * @Route("/admin/account/list", name="gui__admin_account_list")
     */
    public function expose(): Response
    {
        $accounts = $this->accountService->getAll();
        return $this->render('admin/account/account_list.html.twig', ['accounts' => $accounts]);
    }
}
