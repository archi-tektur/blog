<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Service\EntityService\ArticleService;
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
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     * @var ArticleService
     */
    protected $articleService;

    public function __construct(EntityManagerInterface $entityManager, ArticleService $articleService)
    {
        $this->entityManager = $entityManager;
        $this->articleService = $articleService;
    }

    /**
     * @Route("/admin/article/add", name="gui__admin_article_add")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function add(Request $request)
    {
        $form = $this->createForm(ArticleFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Article $article */
            $article = $form->getData();
            return $this->redirectToRoute('gui__admin_article_list');

        }
        return $this->render('admin/article/article_add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/article/list", name="gui__admin_article_list")
     */
    public function expose(): Response
    {
        return $this->render('admin/article/article_list.html.twig', [
            'articles' => $this->articleService->getAll(),
        ]);
    }
}
