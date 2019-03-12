<?php declare(strict_types=1);

namespace App\DataFixtures\SimpleEntityFixtures;

use App\Service\EntityService\AccountService;
use Exception;
use function sprintf;

/**
 * Class AccountFixture
 *
 * @package App\DataFixtures\SimpleEntityFixtures
 */
class AccountFixture extends AbstractIteratedFixture
{
    private const ACCOUNT_NAME = 'Name';
    private const ACCOUNT_SURNAME = 'Surname';
    private const ACCOUNT_EMAIL = 'test%s@domain.net';
    private const ACCOUNT_PROF_PATH = '/img/automated/img%s.jpg';
    private const ACCOUNT_PASSWORD = 'P@$$w0rd';
    /**
     * @var AccountService
     */
    protected $accountService;

    /**
     * AccountFixture constructor.
     *
     * @param AccountService $accountService
     */
    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Function ran each iteration
     *
     * @param int $iterator
     * @return mixed
     * @throws Exception
     */
    protected function eachIter(int $iterator): void
    {
        $mail = sprintf(self::ACCOUNT_EMAIL, $iterator);
        $profPath = sprintf(self::ACCOUNT_PROF_PATH, $iterator);

        $this->accountService->add(
            self::ACCOUNT_NAME . $iterator,
            self::ACCOUNT_SURNAME . $iterator,
            $mail,
            $profPath,
            self::ACCOUNT_PASSWORD
        );
    }
}
