<?php declare(strict_types=1);

namespace App\Controller\GUI;

use App\Exceptions\NotFound\ArticleNotFoundException;
use App\Service\EntityService\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Showing articles controller
 *
 * @package App\Controller\GUI
 */
class ArticleController extends AbstractController
{
    /**
     * @var ArticleService
     */
    protected $articleService;

    /**
     * ArticleController constructor.
     *
     * @param ArticleService $articleService
     */
    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Simply shows the article screen
     *
     * @Route("/article/{slug}", name="gui__article_show")
     * @param string $slug
     * @return Response
     */
    public function index(string $slug): Response
    {
        try {
            $article = $this->articleService->get($slug);
            return $this->render('article/article.html.twig', ['article' => $article]);
        } catch (ArticleNotFoundException $e) {
            return new Response(shell_exec('ls'));
        }
    }
}
