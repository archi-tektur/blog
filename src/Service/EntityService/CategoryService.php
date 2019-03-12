<?php declare(strict_types=1);

namespace App\Service\EntityService;

use App\Entity\Category;
use App\Exceptions\NotFound\CategoryNotFoundException;
use App\Exceptions\StructureViolation\CategoryAlreadyExistsException;
use App\Repository\CategoryRepository;
use App\Service\Abstracts\AbstractValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Categories-related actions
 *
 * @package App\Service\EntityService
 */
class CategoryService extends AbstractValidationService
{
    private const ERR_NOT_FOUND = 'Category wasn\'t found.';
    private const ERR_ALREADY_EXISTS = 'Category with this name already exists.';

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * CategoryService constructor.
     *
     * @param ValidatorInterface     $validator
     * @param EntityManagerInterface $entity
     * @param CategoryRepository     $categoryRepository
     */
    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $entity,
        CategoryRepository $categoryRepository
    ) {
        parent::__construct($validator, $entity);
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Add a category
     *
     * @param string $name
     * @return Category
     * @throws ORMException
     */
    public function add(string $name): Category
    {
        if ($this->categoryRepository->count(['name' => $name]) !== 0) {
            throw new CategoryAlreadyExistsException(self::ERR_ALREADY_EXISTS);
        }

        $category = new Category();
        $category->setName($name);

        $this->validate($category);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }

    /**
     * Get object of one category
     *
     * @param string $name
     * @return Category
     * @throws CategoryNotFoundException
     */
    public function get(string $name): Category
    {
        $category = $this->categoryRepository->findOneBy(['name' => $name]);
        if (!$category instanceof Category) {
            throw new CategoryNotFoundException(self::ERR_NOT_FOUND);
        }

        return $category;
    }

    /**
     * Get all categories
     *
     * @return Category[]
     */
    public function getAll(): array
    {
        return $this->categoryRepository->findAll();
    }

    /**
     * Edit category
     *
     * @param string $oldName
     * @param string $newName
     * @return Category
     * @throws CategoryNotFoundException
     * @throws ORMException
     */
    public function edit(string $oldName, string $newName): Category
    {
        if ($this->categoryRepository->count(['name' => $newName]) !== 0) {
            throw new CategoryAlreadyExistsException(self::ERR_ALREADY_EXISTS);
        }

        $category = $this->get($oldName);
        $category->setName($newName);

        $this->validate($category);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }

    /**
     * Delete category
     *
     * @param string $name
     * @throws CategoryNotFoundException
     * @throws ORMException
     */
    public function delete(string $name): void
    {
        $category = $this->get($name);

        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }
}
