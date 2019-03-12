<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use App\Entity\Article;
use App\Form\ArticleFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 *
 * @package App\Controller\GUI\AdminPanel
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/admin/article/add", name="gui__admin_article_add")
     * @param EntityManagerInterface $em
     * @param Request                $request
     * @return RedirectResponse|Response
     */
    public function add(EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(ArticleFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Article $article */
            $article = $form->getData();
            return $this->redirectToRoute('gui__admin_article_add');

        }
        return $this->render('base.html.twig', ['form' => $form->createView()]);
    }
}
