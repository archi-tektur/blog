<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Service\EntityService\ArticleService;
use App\Service\UploaderService\ArticleShowreelUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    /**
     * @var ArticleShowreelUploader
     */
    protected $articleShowreelUploader;

    /**
     * @param EntityManagerInterface  $entityManager
     * @param ArticleService          $articleService
     * @param ArticleShowreelUploader $articleShowreelUploader
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ArticleService $articleService,
        ArticleShowreelUploader $articleShowreelUploader
    ) {
        $this->entityManager = $entityManager;
        $this->articleService = $articleService;
        $this->articleShowreelUploader = $articleShowreelUploader;
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/admin/article/add", name="gui__admin_article_add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $form = $this->createForm(ArticleFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();

            // add actual user as author too
            $article->addAuthor($this->getUser());

            // perform on database
            $this->entityManager->persist($article);
            $this->entityManager->flush();
        }
        return $this->render('admin/panels/addarticle.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/admin/articles/all/", name="gui__admin_all_articles")
     */
    public function index(): Response
    {
        return $this->render('admin/panels/all-articles.html.twig');
    }
}
