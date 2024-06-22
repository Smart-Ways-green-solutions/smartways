<?php

namespace App\Controller;

use Pimcore\Bundle\AdminBundle\Controller\Admin\LoginController;
use Pimcore\Controller\FrontendController;
use Sabre\Xml\Element\Base;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/home', name: 'home')]
    public function defaultAction(Request $request): Response
    {
        return $this->render('default/default.html.twig');
    }


//    public function loginAction(): Response
//    {
//        return $this->forward(LoginController::class . '::loginCheckAction');
//    }
}
