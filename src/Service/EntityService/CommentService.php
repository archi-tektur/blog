<?php declare(strict_types=1);

namespace App\Service\EntityService;

use App\Entity\Account;
use App\Entity\Article;
use App\Entity\Comment;
use App\Exceptions\AccessViolation\CommentAccessViolationException;
use App\Exceptions\NotFound\CommentNotFoundException;
use App\Repository\CommentRepository;
use App\Service\Abstracts\AbstractValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Comment-related actions
 *
 * @package App\Service\EntityService
 */
class CommentService extends AbstractValidationService
{
    private const ERR_NOT_FOUND = 'Comment with that ID wasn\'t found.';
    private const ERR_NO_ACCESS = 'It seems that you have no access to edit this comment.';

    /**
     * @var CommentRepository
     */
    protected $commentRepository;

    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $entity,
        CommentRepository $commentRepository
    ) {
        parent::__construct($validator, $entity);
        $this->commentRepository = $commentRepository;
    }

    /**
     * Adds simple comment
     *
     * @param string  $content
     * @param Account $author
     * @param Article $article
     * @return Comment
     * @throws ORMException
     */
    public function add(string $content, Account $author, Article $article): Comment
    {
        $comment = new Comment();

        $comment->setContent($content)
                ->setAuthor($author)
                ->setArticle($article);
        $this->validate($comment);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return $comment;
    }

    /**
     * Get details of one comment
     *
     * @param int $identifier
     * @return Comment
     * @throws CommentNotFoundException
     * @throws CommentAccessViolationException
     */
    public function get(int $identifier): Comment
    {
        $comment = $this->commentRepository->find($identifier);
        if (!$comment instanceof Comment) {
            throw new CommentNotFoundException(self::ERR_NOT_FOUND);
        }

        return $comment;
    }

    /**
     * Get all comments ever created
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->commentRepository->findAll();
    }

    /**
     * @param int     $identifier
     * @param string  $content
     * @param Account $editor person that runs command
     * @return Comment
     * @throws CommentNotFoundException
     * @throws CommentAccessViolationException
     * @throws ORMException
     */
    public function edit(int $identifier, string $content, Account $editor): Comment
    {
        $comment = $this->get($identifier);
        if ($comment->getAuthor() !== $editor) {
            throw new CommentAccessViolationException(self::ERR_NO_ACCESS);
        }

        $comment->setContent($content);
        $this->validate($comment);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return $comment;
    }

    /**
     * @param int     $identifier
     * @param Account $editor person that runs command
     * @throws CommentNotFoundException
     * @throws CommentAccessViolationException
     * @throws ORMException
     */
    public function delete(int $identifier, Account $editor): void
    {
        $comment = $this->get($identifier);
        if ($comment->getAuthor() !== $editor) {
            throw new CommentAccessViolationException(self::ERR_NO_ACCESS);
        }

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }
}
