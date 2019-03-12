<?php declare(strict_types=1);

namespace App\Security;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    /**
     * POST key names
     */
    private const EMAIL_FIELD_NAME = '_email';
    private const PSWD_FIELD_NAME = '_password';
    private const CSRF_FIELD_NAME = '_csrf';
    /**
     * Internal key names
     */
    private const INTERNAL_EMAIL = 'mail';
    private const INTERNAL_PSWD = 'password';
    private const INTERNAL_CSRF = 'csrf';
    /**
     * Error messages
     */
    private const ENTITY_NOT_FOUND = 'User with this e-mail not found.';
    private const PSWD_WRONG = 'Password is incorrect.';
    private const CSRF_FAILURE = 'Invalid CSRF token provided.';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;

    /**
     * @inheritdoc
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @inheritdoc
     */
    public function supports(Request $request): bool
    {
        return 'gui_admin_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    /**
     * @inheritdoc
     */
    public function getCredentials(Request $request)
    {
        $credentials = [
            self::INTERNAL_EMAIL => $request->request->get(self::EMAIL_FIELD_NAME),
            self::INTERNAL_PSWD  => $request->request->get(self::PSWD_FIELD_NAME),
            self::INTERNAL_CSRF  => $request->request->get(self::CSRF_FIELD_NAME),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials[self::INTERNAL_EMAIL]
        );

        return $credentials;
    }

    /**
     * @inheritdoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials[self::INTERNAL_CSRF]);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException(self::CSRF_FAILURE);
        }
        $user = $this->entityManager->getRepository(Account::class)
                                    ->findOneBy(['email' => $credentials[self::INTERNAL_EMAIL]]);
        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException(self::ENTITY_NOT_FOUND);
        }
        return $user;
    }

    /**
     * @inheritdoc
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        $boolean = $this->passwordEncoder->isPasswordValid($user, $credentials[self::INTERNAL_PSWD]);
        if ($boolean) {
            return $boolean;
        }
        throw new AuthenticationException(self::PSWD_WRONG);
    }

    /**
     * @inheritdoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetPath = $this->getTargetPath($request->getSession(), $providerKey);
        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }
        return new RedirectResponse($this->urlGenerator->generate('gui__admin_article_list'));
    }

    /**
     * @inheritdoc
     */
    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate('gui_admin_login');
    }
}
