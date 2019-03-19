<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use App\Form\CategoryFormType;
use App\Service\EntityService\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 *
 * @package App\Controller\GUI\AdminPanel
 */
class CategoryController extends AbstractController
{
    /**
     * @var CategoryService
     */
    protected $categoryService;
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(CategoryService $categoryService, EntityManagerInterface $entityManager)
    {
        $this->categoryService = $categoryService;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/categories", name="gui__categories_index")
     * @return Response
     */
    public function index(): Response
    {
        $newCategoryForm = $this->createForm(CategoryFormType::class);

        return $this->render('admin/panels/categories.html.twig', [
            'newCategoryForm' => $newCategoryForm->createView(),
        ]);
    }
}
