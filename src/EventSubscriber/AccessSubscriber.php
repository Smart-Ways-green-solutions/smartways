<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\SecurityBundle\Security;
use App\Controller\Administration\BaseAdministrationController;

class AccessSubscriber implements EventSubscriberInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        // Controller can be a callable (class + method), so handle this
        if (is_array($controller)) {
            $controllerObject = $controller[0];
          
            // Check if the controller is of type SupplierController
            if ($controllerObject instanceof BaseAdministrationController) {
            
                $user = $this->security->getUser();

                // If user is not a Supplier Admin, throw 404
                if (!$user || !$user->getPermission_admin()) {
                   
                    throw new NotFoundHttpException('Page not found');
                }
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
