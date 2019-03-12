<?php declare(strict_types=1);

namespace App\Service\RelationService;

use App\Entity\Account;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Author actions on Articles
 *
 * @package App\Service\RelationService
 */
class ArticleAuthorsService
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
     * @param Article $article
     * @param Account $account
     */
    public function add(Article $article, Account $account): void
    {
        $article->addAuthor($account);

        $this->entityManager->persist($article);
        $this->entityManager->flush();
    }

    /**
     * @param Article $article
     * @param Account $account
     */
    public function remove(Article $article, Account $account): void
    {
        $article->removeAuthor($account);

        $this->entityManager->persist($article);
        $this->entityManager->flush();
    }

    /**
     * Returns true if article has $category, false if not.
     *
     * @param Article $article
     * @param Account $account
     * @return bool
     */
    public function has(Article $article, Account $account): bool
    {
        return $article->getAuthors()->contains($account);
    }
}
