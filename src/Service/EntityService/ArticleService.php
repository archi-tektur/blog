<?php declare(strict_types=1);

namespace App\Service\EntityService;

use App\Entity\Account;
use App\Entity\Article;
use App\Entity\Category;
use App\Exceptions\NotFound\ArticleAlreadyExistsException;
use App\Exceptions\NotFound\ArticleNotFoundException;
use App\Repository\ArticleRepository;
use App\Service\Abstracts\AbstractValidationService;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ArticleService
 *
 * @package App\Service\EntityService
 */
class ArticleService extends AbstractValidationService
{
    private const ERR_NOT_FOUND = 'Article wasn\'t found.';
    private const ERR_ALREADY_EXISTS = 'Account with this slug already exists.';
    /**
     * @var ArticleRepository
     */
    private $articleRepository;
    /**
     * @var Slugify
     */
    private $slugify;

    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $entity,
        ArticleRepository $articleRepository,
        Slugify $slugify
    ) {
        parent::__construct($validator, $entity);
        $this->articleRepository = $articleRepository;
        $this->slugify = $slugify;
    }

    /**
     * Add article
     *
     * @param string   $title
     * @param string   $content
     * @param Category $category
     * @param Account  $author
     * @return Article
     * @throws ORMException
     */
    public function add(string $title, string $content, Category $category, Account $author): Article
    {
        $article = new Article();

        $slug = $this->slugify->slugify($title);

        $article->setTitle($title)
                ->setContent($content)
                ->addAuthor($author)
                ->addCategory($category)
                ->setSlug($slug);

        if ($this->articleRepository->count(['slug' => $slug]) !== 0) {
            throw new ArticleAlreadyExistsException(self::ERR_ALREADY_EXISTS);
        }

        $this->validate($article);

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $article;
    }

    /**
     * @return Article[]
     */
    public function getAll(): array
    {
        return $this->articleRepository->findAll();
    }

    /**
     * Edit article details
     *
     * @param string   $slug
     * @param string   $title
     * @param string   $content
     * @param Category $category
     * @param Account  $account
     * @return Article
     * @throws ArticleNotFoundException
     * @throws ORMException
     */
    public function edit(string $slug, string $title, string $content, Category $category, Account $account): Article
    {
        $article = $this->get($slug);

        $newSlug = $this->slugify->slugify($title);

        $article->setTitle($title)
                ->setContent($content)
                ->addCategory($category)
                ->addAuthor($account)
                ->setSlug($newSlug);

        $this->validate($article);

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $article;
    }

    /**
     * Get article details
     *
     * @param string $slug
     * @return Article
     * @throws ArticleNotFoundException
     */
    public function get(string $slug): Article
    {
        $article = $this->articleRepository->findOneBy(['slug' => $slug]);
        if (!$article instanceof Article) {
            throw new ArticleNotFoundException(self::ERR_NOT_FOUND);
        }

        return $article;
    }

    /**
     * Deletes an article
     *
     * @param string $slug
     * @throws ArticleNotFoundException
     * @throws ORMException
     */
    public function delete(string $slug): void
    {
        $article = $this->get($slug);

        $this->entityManager->remove($article);
        $this->entityManager->flush();
    }
}
