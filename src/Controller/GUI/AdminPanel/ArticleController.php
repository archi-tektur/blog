<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use App\Entity\Article;
use App\Exceptions\NotFound\AccountNotFoundException;
use App\Exceptions\NotFound\ArticleNotFoundException;
use App\Exceptions\NotFound\CategoryNotFoundException;
use App\Form\ArticleFormType;
use App\Service\EntityService\ArticleService;
use App\Service\UploaderService\ArticleShowreelUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * Class ArticleController
 *
 * @package App\Controller\GUI\AdminPanel
 */
class ArticleController extends AbstractController
{
    public const FLASH_PREFIX = 'article_';

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
    public function all(): Response
    {
        $articles = $this->articleService->getAll();
        return $this->render('admin/panels/all-articles.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/admin/articles/byCategory/{categoryName}", name="gui__admin_all_bycategory")
     * @param $categoryName
     * @return Response
     */
    public function byCategory($categoryName): Response
    {
        try {
            $articles = $this->articleService->getByCategory($categoryName);
            return $this->render('admin/panels/all-articles.html.twig', ['articles' => $articles]);
        } catch (CategoryNotFoundException $e) {
            return $this->redirectToRoute('gui__admin_all_articles');
        }
    }

    /**
     * @Route("/admin/articles/byAuthor/{authorMail}", name="gui__admin_all_byauthor")
     * @param $authorMail
     * @return Response
     */
    public function byAuthor($authorMail): Response
    {
        try {
            $articles = $this->articleService->getByAuthor($authorMail);
            return $this->render('admin/panels/all-articles.html.twig', [
                'articles'      => $articles,
                'sortingMethod' => 'by author ' . $authorMail,
            ]);
        } catch (AccountNotFoundException $e) {
            return $this->redirectToRoute('gui__admin_all_articles');
        }
    }

    /**
     * @Route("/admin/articles/{slug}/visibilityChange", name="gui__admin_article_vischange")
     * @return RedirectResponse
     */
    public function changeVisibility($slug): RedirectResponse
    {
        return $this->redirectToRoute('gui__admin_all_articles');
    }

    /**
     * @Route("/admin/articles/{slug}/details", name="gui__admin_article_details")
     * @return RedirectResponse
     */
    public function details($slug): RedirectResponse
    {
        return $this->redirectToRoute('gui__admin_all_articles');
    }

    /**
     * Deletes
     *
     * @Route("/admin/articles/{slug}/delete", name="gui__admin_article_delete")
     * @param $slug
     * @return RedirectResponse
     */
    public function delete($slug): RedirectResponse
    {
        try {
            $this->articleService->delete($slug);
            // notify about success
            $this->addFlash(self::FLASH_PREFIX . 'success', 'OK');

        } catch (ArticleNotFoundException $e) {
            $this->addFlash(self::FLASH_PREFIX . 'error', $e->getMessage());
        } catch (Throwable $e) {
            // possible ORM database error
            $this->addFlash(self::FLASH_PREFIX . 'error', $e->getMessage());
        } finally {
            return $this->redirectToRoute('gui__admin_all_articles');
        }
    }
}
