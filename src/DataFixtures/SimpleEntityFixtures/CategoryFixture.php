<?php declare(strict_types=1);

namespace App\DataFixtures\SimpleEntityFixtures;

use App\Service\EntityService\CategoryService;
use Doctrine\ORM\ORMException;

/**
 * Class CategoryFixture
 *
 * @package App\DataFixtures\SimpleEntityFixtures
 */
class CategoryFixture extends AbstractIteratedFixture
{
    private const CATEGORY_NAME = 'CategoryFancyName';

    /**
     * @var CategoryService
     */
    protected $categoryService;

    /**
     * CategoryFixture constructor
     *
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Runned every iteration
     *
     * @param int $iterator
     * @throws ORMException
     */
    protected function eachIter(int $iterator): void
    {
        $this->categoryService->add(self::CATEGORY_NAME . $iterator);
    }
}
