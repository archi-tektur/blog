<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use App\Entity\Account;
use App\Forms\UserFormType;
use App\Renderers\ConfirmScreenRenderer;
use App\Service\EntityService\AccountService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
}
