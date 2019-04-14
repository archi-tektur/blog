<?php declare(strict_types=1);

namespace App\DataFixtures\SimpleEntityFixtures;

use Exception;
use FOS\UserBundle\Model\UserManagerInterface;

/**
 * Class AccountFixture
 *
 * @package App\DataFixtures\SimpleEntityFixtures
 */
class AccountFixture extends AbstractIteratedFixture
{
    private const ACCOUNT_NAME = 'Name';
    private const ACCOUNT_SURNAME = 'Surname';
    private const ACCOUNT_LOGIN = 'username';
    private const ACCOUNT_EMAIL = 'test%s@domain.net';
    private const ACCOUNT_PROF_PATH = '/img/automated/img%s.jpg';
    private const ACCOUNT_PASSWORD = 'P@$$w0rd';

    protected $iterationsCount = 5;
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * AccountFixture constructor.
     *
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
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
        $mail = $this->iterReplace(self::ACCOUNT_EMAIL);
        $profPath = $this->iterReplace(self::ACCOUNT_PROF_PATH);

    }
}
