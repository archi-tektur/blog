<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use App\Entity\Category;
use App\Exceptions\NotFound\CategoryNotFoundException;
use App\Form\CategoryFormType;
use App\Renderers\ConfirmScreenRenderer;
use App\Service\EntityService\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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
    public const FLASH_PREFIX = 'category_';

    /**
     * @var CategoryService
     */
    protected $categoryService;
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     * @var ConfirmScreenRenderer
     */
    protected $confirmScreenRenderer;

    public function __construct(
        CategoryService $categoryService,
        EntityManagerInterface $entityManager,
        ConfirmScreenRenderer $confirmScreenRenderer
    ) {
        $this->categoryService = $categoryService;
        $this->entityManager = $entityManager;
        $this->confirmScreenRenderer = $confirmScreenRenderer;
    }

    /**
     * Main summary screen render
     *
     * @Route("/admin/categories", name="gui__admin_categories_index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // create form
        $addForm = $this->createForm(CategoryFormType::class);
        $editForm = $this->createForm(CategoryFormType::class);
        $addForm->handleRequest($request);
        // handle further request
        $this->add($addForm);

        // download after addition
        $categories = $this->categoryService->getAll();
        $addForm = $this->createForm(CategoryFormType::class);

        return $this->render('admin/panels/categories.html.twig', [
            'categories'       => $categories,
            'addCategoryForm'  => $addForm->createView(),
            'editCategoryForm' => $editForm->createView(),
        ]);
    }

    /**
     * Renderless method to delete a category
     *
     * @Route("/admin/categories/{name}/delete", name="gui__admin_categories_delete")
     * @param string $name
     * @return Response
     */
    public function delete(string $name): Response
    {
        try {
            // try to delete
            $this->categoryService->delete($name);
        } catch (CategoryNotFoundException $e) {
            // when category is not found
            $this->addFlash(self::FLASH_PREFIX . 'warning', 'Category not found!');
        } catch (ORMException $e) {
            $this->addFlash('warning', 'Database problem occured');
        } finally {
            return $this->redirectToRoute('gui__admin_categories_index');
        }

    }

    /**
     * Adds category or adds error
     *
     * @param FormInterface $addForm
     */
    private function add(FormInterface $addForm): void
    {
        // when form is valid
        if ($addForm->isSubmitted() && $addForm->isValid()) {
            // Handling add form
            /** @var Category $category */
            $category = $addForm->getData();
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->addFlash(self::FLASH_PREFIX . 'success', 'OK');
        }

        // when form is not valid
        if ($addForm->isSubmitted() && !$addForm->isValid()) {
            foreach ($addForm->getErrors(true) as $error) {
                $this->addFlash(self::FLASH_PREFIX . 'warning', $error->getMessage());
            }
        }
    }
}
