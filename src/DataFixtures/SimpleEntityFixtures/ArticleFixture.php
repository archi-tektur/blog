<?php
/**
 * Created by PhpStorm.
 * User: archi-tektur
 * Date: 3/12/19
 * Time: 2:37 AM
 */

namespace App\DataFixtures\SimpleEntityFixtures;

use App\Service\EntityService\AccountService;
use App\Service\EntityService\ArticleService;
use Doctrine\ORM\ORMException;

/**
 * Class ArticleFixture
 *
 * @package App\DataFixtures\SimpleEntityFixtures
 */
class ArticleFixture extends AbstractIteratedFixture
{
    private const ARTICLE_TITLE = 'Fancy article no. %s by user %s';
    private const ARTICLE_CONTENT = '<h1>Article no. %s</h1>';

    protected $iterationsCount = 5;
    /**
     * @var AccountService
     */
    protected $accountService;
    /**
     * @var ArticleService
     */
    protected $articleService;
    private $generalIterator = 0;

    public function __construct(AccountService $accountService, ArticleService $articleService)
    {
        $this->accountService = $accountService;
        $this->articleService = $articleService;
    }

    /**
     * Function ran each iteration
     *
     * @param int $iterator
     * @return mixed
     * @throws ORMException
     */
    protected function eachIter(int $iterator): void
    {
        // create for each user X articles (SLOW!)
        foreach ($this->accountService->getAll() as $iteration => $account) {
            $this->generalIterator++;
            $this->articleService->add(
                sprintf(self::ARTICLE_TITLE, $iterator, $iteration),
                sprintf(self::ARTICLE_CONTENT, $this->generalIterator),
                $account
            );
        }
    }
}
