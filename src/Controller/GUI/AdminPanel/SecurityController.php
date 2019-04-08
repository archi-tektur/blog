<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use App\DTO\ConfirmScreenConfig;
use App\Entity\Account;
use App\Form\RegisterFormType;
use App\Renderers\ConfirmScreenRenderer;
use App\Security\LoginFormAuthenticator;
use App\Service\EntityService\AccountService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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

    /**
     * @Route("/login", name="gui_admin_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
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

    /**
     * @Route("/logout", name="gui__app-logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/register/", name="gui__admin_register")
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler    $guardHandler
     * @param LoginFormAuthenticator       $formAuthenticator
     * @return Response
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $formAuthenticator
    ): Response {
        $form = $this->createForm(RegisterFormType::class);
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

            return $guardHandler->authenticateUserAndHandleSuccess(
                $account,
                $request,
                $formAuthenticator,
                'main'
            );

        }

        return $this->render('admin/security/register.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Test confirm screen
     * @Route("/admin/confirm")
     */
    public function confirm(): Response
    {
        $config = new ConfirmScreenConfig();
        $config->setTranslatable(true);
        return $this->confirmRenderer->run($config);
    }
}
