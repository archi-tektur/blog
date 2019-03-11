<?php declare(strict_types=1);

namespace App\Service\EntityService;

use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Service\Abstracts\AbstractValidationService;
use App\Tools\RandomStringGenerator;
use Doctrine\ORM\EntityManager;
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

    public function __construct(
        AccountRepository $accountRepository,
        UserPasswordEncoderInterface $encoder,
        ValidatorInterface $validator,
        EntityManager $entityManager
    ) {
        parent::__construct($validator, $entityManager);
        $this->accountRepository = $accountRepository;
        $this->encoder = $encoder;
    }

    /**
     * Adds account to database
     *
     * @param string $name
     * @param string $surname
     * @param string $profilePath
     * @param string $password
     * @return Account
     * @throws Exception
     */
    public function add(string $name, string $surname, string $profilePath, string $password): Account
    {
        $account = new Account();

        $encodedPassword = $this->encoder->encodePassword($account, $password);
        $apiKey = $this->generateApiPartialKey();

        // TODO: check if path is correct

        $account->setName($name)
                ->setSurname($surname)
                ->setProfilePicturePath($profilePath)
                ->setPassword($encodedPassword)
                ->setApiPartialKey($apiKey);

        $this->validate($account);

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        return $account;
    }

    /**
     * Get information about account
     *
     * @param string $email
     * @return Account
     * @throws AccountNotFoundException
     */
    public function get(string $email): Account
    {
        $account = $this->accountRepository->findOneBy(['email' => $email]);
        if (!$account instanceof Account) {
            throw new AccountNotFoundException(self::ERR_NOT_FOUND);
        }

        return $account;
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
                ->setProfilePicturePath($profilePath);

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        return $account;
    }

    /**
     * @param string $email
     * @throws AccountNotFoundException
     * @throws ORMException
     */
    public function delete(string $email): void
    {
        $account = $this->get($email);
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
