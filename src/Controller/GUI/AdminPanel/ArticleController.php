<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use App\Entity\Article;
use App\EventListener\Doctrine\ArticleListener;
use App\Exceptions\NotFound\ArticleNotFoundException;
use App\Form\ArticleFormType;
use App\Service\EntityService\ArticleService;
use App\Service\UploaderService\ArticleShowreelUploader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @return RedirectResponse|Response
     */
    public function add(Request $request)
    {
        $form = $this->createForm(ArticleFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();
            $article->addAuthor($this->getUser());
            /**
             * While analysing this code don't forget to check
             *
             * @see ArticleShowreelUploader
             * @see ArticleListener
             */
            $this->entityManager->persist($article);
            $this->entityManager->flush();
            return $this->redirectToRoute('gui__admin_article_list');
        }
        return $this->render('admin/article/article_add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/admin/article/{slug}/edit", name="gui__admin_article_edit")
     * @param Request $request
     * @param string  $slug
     * @return Response
     * @throws ArticleNotFoundException
     */
    public function edit(Request $request, string $slug): Response
    {
        $article = $this->articleService->get($slug);
        $oldPicture = $article->getShowreelImage();
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);
        /**
         * While analysing this code don't forget to check
         *
         * @see ArticleShowreelUploader
         * @see ArticleListener
         */
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();
            $article->addAuthor($this->getUser());
            // don't reset image if new weren't set
            if (!$article->getShowreelImage()) {
                $article->setShowreelImage($oldPicture);
            }
            $this->entityManager->persist($article);
            $this->entityManager->flush();
            return $this->redirectToRoute('gui__admin_article_list');
        }
        return $this->render('admin/article/article_edit.html.twig', ['form' => $form->createView()]);

    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/admin/article/{slug}/delete", name="gui__admin_article_delete")
     * @param string $slug
     * @return RedirectResponse
     * @throws ArticleNotFoundException
     * @throws ORMException
     */
    public function delete(string $slug): RedirectResponse
    {
        $this->articleService->delete($slug);
        return $this->redirectToRoute('gui__admin_article_list');
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/admin/article/list", name="gui__admin_article_list")
     */
    public function expose(): Response
    {
        return $this->render('admin/article/article_list.html.twig', [
            'articles' => $this->articleService->getAll(),
        ]);
    }
}
