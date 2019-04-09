<?php declare(strict_types=1);

namespace App\EventSubscriber\FOSUserBundle;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

/**
 * Redirecting user to users panel after successful registration
 *
 * @package App\EventSubscriber\FOSUserBundle
 */
class RedirectAfterRegistrationSubscriber implements EventSubscriberInterface
{

    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /** @inheritDoc */
    public static function getSubscribedEvents(): array
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        ];
    }

    public function onRegistrationSuccess(FormEvent $event): void
    {
        $url = $this->router->generate('gui__admin_users');
        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }
}
