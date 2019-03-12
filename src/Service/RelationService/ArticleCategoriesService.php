<?php declare(strict_types=1);

namespace App\Service\RelationService;


use App\Entity\Article;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Category actions on Articles
 *
 * @package App\Service\RelationService
 */
class ArticleCategoriesService
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Article  $article
     * @param Category $category
     */
    public function add(Article $article, Category $category): void
    {
        $article->addCategory($category);

        $this->entityManager->persist($article);
        $this->entityManager->flush();
    }

    /**
     * @param Article  $article
     * @param Category $category
     */
    public function remove(Article $article, Category $category): void
    {
        $article->removeCategory($category);

        $this->entityManager->persist($article);
        $this->entityManager->flush();
    }

    /**
     * Returns true if article has $category, false if not.
     *
     * @param Article  $article
     * @param Category $category
     * @return bool
     */
    public function has(Article $article, Category $category): bool
    {
        return $article->getCategories()->contains($category);
    }
}
