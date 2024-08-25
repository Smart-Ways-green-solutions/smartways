<?php
namespace App\EventListener;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

/**
 * Authentication listener to set correct user to e-commerce framework environment after login and track login activity
 */
class AuthenticationLoginListener implements EventSubscriberInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }

    public function __construct()
    {
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param LoginSuccessEvent $event
     *
     * @return void
     */
    public function onLoginSuccess(LoginSuccessEvent $event): void
    {

    }


}
