<?php

namespace App\Controller\Lager;

use App\Controller\BaseController;
use App\Model\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LagerController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/lager/lagerverwaltung', name: 'lager')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function lagerAction(Request $request): Response
    {
        $this->checkPermission($this->getUser(), ["wegepate"]);

        return $this->render('', [

        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/lager/lagerinhalt', name: 'lager-inhalt')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function lagerInhaltAction(Request $request): Response
    {
        return $this->render('');
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/lager/lagerhistorie', name: 'lager-historie')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function lagerHistorieAction(Request $request): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        return $this->render('lager/lager_historie.html.twig');
    }


}
