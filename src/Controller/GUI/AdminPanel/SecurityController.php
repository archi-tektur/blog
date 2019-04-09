<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use App\DTO\ConfirmScreenConfig;
use App\Entity\Account;
use App\Forms\UserFormType;
use App\Renderers\ConfirmScreenRenderer;
use App\Service\EntityService\AccountService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Handling security actions on app
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
     * @var ConfirmScreenRenderer
     */
    protected $confirmRenderer;

    /**
     * SecurityController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param AccountService         $accountService
     * @param ConfirmScreenRenderer  $confirmRenderer
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        AccountService $accountService,
        ConfirmScreenRenderer $confirmRenderer
    ) {
        $this->entityManager = $entityManager;
        $this->accountService = $accountService;
        $this->confirmRenderer = $confirmRenderer;
    }

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/security/login.html.twig', [
                'email' => $lastUsername,
                'error' => $error,
            ]
        );
    }

    public function logout(): void
    {
    }

    /**
     * @Route("/admin/add-new-user", name="gui__admin_add-new-user")
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function addNewUser(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Account $account */
            $account = $form->getData();
            $account->setPassword($passwordEncoder->encodePassword(
                $account,
                $form['plainPassword']->getData()
            ));

            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();

            return $this->redirectToRoute('gui__admin_users');
        }

        return $this->render('admin/forms/new-user-form.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Test confirm screen
     * @Route("/admin/confirm")
     */
    public
    function confirm(): Response
    {
        $config = new ConfirmScreenConfig();
        $config->setTranslatable(true);
        return $this->confirmRenderer->renderConfirmScreen($config);
    }
}
