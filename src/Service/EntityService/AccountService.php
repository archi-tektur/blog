<?php declare(strict_types=1);

namespace App\Service\EntityService;

use App\Entity\Account;
use App\Exceptions\NotFound\AccountNotFoundException;
use App\Exceptions\StructureViolation\CannotDeleteOwnAccountException;
use App\Repository\AccountRepository;
use App\Service\Abstracts\AbstractValidationService;
use App\Tools\RandomStringGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Account-related actions
 *
 * @package App\Service\EntityService
 */
class AccountService extends AbstractValidationService
{
    /**
     * @var AccountRepository
     */
    protected $accountRepository;
    /**
     * @var PasswordEncoderInterface
     */
    protected $encoder;

    private const ERR_NOT_FOUND = 'Account wasn\'t found.';
    private const ERR_ALREADY_EXISTS = 'Account with this e-mail already exists.';
    private const ERR_DELETING_OWN = 'You cannot delete yourself.';

    public function __construct(
        AccountRepository $accountRepository,
        UserPasswordEncoderInterface $encoder,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($validator, $entityManager);
        $this->accountRepository = $accountRepository;
        $this->encoder = $encoder;
    }

    /**
     * Get information about account
     *
     * @param string $login
     * @return Account
     * @throws AccountNotFoundException
     */
    public function get(string $login): Account
    {
        $account = $this->accountRepository->findOneBy(['username' => $login]);
        if (!$account instanceof Account) {
            throw new AccountNotFoundException(self::ERR_NOT_FOUND);
        }

        return $account;
    }

    /**
     * @return Account[]
     */
    public function getAll(): array
    {
        return $this->accountRepository->findAll();
    }

    /**
     * Edits existing account
     *
     * @param string $email
     * @param string $profilePath
     * @param string $password
     * @return Account
     * @throws AccountNotFoundException
     * @throws Exception
     */
    public function edit(string $email, string $profilePath, string $password): Account
    {
        $account = $this->get($email);

        // TODO: check if path is correct

        $apiKey = $this->generateApiPartialKey();
        $encodedPassword = $this->encoder->encodePassword($account, $password);

        $account->setApiPartialKey($apiKey)
                ->setPassword($encodedPassword)
                ->setProfileImage($profilePath);

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        return $account;
    }

    /**
     * @param Account $account
     * @return Account
     * @throws ORMException
     */
    public function grantUser(Account $account): Account
    {
        $account->setRoles(['ROLE_ADMIN']);

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        return $account;
    }

    /**
     * @param string $email
     * @return Account
     * @throws AccountNotFoundException
     * @throws ORMException
     * @throws Exception
     */
    public function regenerateApiKey(string $email): Account
    {
        $account = $this->get($email);
        $account->setApiPartialKey($this->generateApiPartialKey());
        $this->entityManager->persist($account);
        $this->entityManager->flush();

        return $account;
    }

    /**
     * @param string  $login
     * @param Account $deleter
     * @throws AccountNotFoundException
     * @throws ORMException
     * @throws CannotDeleteOwnAccountException
     * @throws OptimisticLockException
     */
    public function delete(string $login, Account $deleter): void
    {
        $account = $this->get($login);
        if ($account === $deleter) {
            throw new CannotDeleteOwnAccountException(self::ERR_DELETING_OWN);
        }
        $this->entityManager->remove($account);
        $this->entityManager->flush();
    }

    /**
     * Generates key that does not exist in database yet
     *
     * @return string
     * @throws Exception
     */
    private function generateApiPartialKey(): string
    {
        do {
            $apiKey = RandomStringGenerator::generate(64);
        } while ($this->accountRepository->count(['apiPartialKey' => $apiKey]) !== 0);

        return $apiKey;
    }
}
