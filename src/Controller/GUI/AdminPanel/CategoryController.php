<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use App\Entity\Category;
use App\Exceptions\NotFound\CategoryNotFoundException;
use App\Form\CategoryFormType;
use App\Service\EntityService\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/admin/categories", name="gui__admin_categories_index")
     * @return Response
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(CategoryFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Category $category */
            $category = $form->getData();
            $this->entityManager->persist($category);
            $this->entityManager->flush();
        }

        $categories = $this->categoryService->getAll();

        $form = $this->createForm(CategoryFormType::class);

        return $this->render('admin/panels/categories.html.twig', [
            'categories'      => $categories,
            'addCategoryForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/categories/{name}/delete", name="gui__admin_categories_delete")
     * @param string $name
     * @return Response
     */
    public function delete(string $name): Response
    {
        try {
            $this->categoryService->delete($name);
        } catch (CategoryNotFoundException $e) {
            $this->addFlash('error', 'Category not found!');
        } catch (ORMException $e) {
            $this->addFlash('error', 'Database problem occured');
        } finally {
            return $this->redirectToRoute('gui__admin_categories_index');
        }

    }
}
