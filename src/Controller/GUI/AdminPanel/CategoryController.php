<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use App\Entity\Category;
use App\Exceptions\NotFound\CategoryNotFoundException;
use App\Form\CategoryFormType;
use App\Service\EntityService\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @Route("/admin/category/add", name="gui__admin_category_add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $form = $this->createForm(CategoryFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Category $category */
            $category = $form->getData();
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            return $this->redirectToRoute('gui__admin_category_list');
        }
        return $this->render('admin/category/category_add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/category/{name}/edit", name="gui__admin_category_edit")
     * @param Request $request
     * @param string  $name
     * @return Response
     * @throws CategoryNotFoundException
     */
    public function edit(Request $request, string $name): Response
    {
        $category = $this->categoryService->get($name);
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Category $category */
            $category = $form->getData();
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            return $this->redirectToRoute('gui__admin_category_list');
        }
        return $this->render('admin/category/category_edit.html.twig', ['form' => $form->createView()]);

    }

    /**
     * @Route("/admin/category/{name}/delete", name="gui__admin_category_delete")
     * @param string $name
     * @return RedirectResponse
     * @throws CategoryNotFoundException
     * @throws ORMException
     */
    public function delete(
        string $name
    ): RedirectResponse {
        $this->categoryService->delete($name);
        return $this->redirectToRoute('gui__admin_category_list');
    }

    /**
     * @Route("/admin/category/list", name="gui__admin_category_list")
     */
    public function expose(): Response
    {
        $categories = $this->categoryService->getAll();
        return $this->render('admin/category/category_list.html.twig', ['categories' => $categories]);
    }
}
