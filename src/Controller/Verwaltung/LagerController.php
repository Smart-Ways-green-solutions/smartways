<?php

namespace App\Controller\Verwaltung;

use App\Controller\BaseController;
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
    #[Route('/verwaltung/lager', name: 'verwaltung_lager')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function lagerAction(Request $request): Response
    {
        return $this->render('verwaltung/lager.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/verwaltung/lager-anlegen', name: 'verwaltung_lager-anlegen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createLagerAction(Request $request): Response
    {
        return $this->render('verwaltung/lager_anlegen.html.twig');
    }

    /**
     * @param Request $request
     * @param int $lagerid
     * @return Response
     */
    #[Route('/verwaltung/lager-bearbeiten/{lagerid}', name: 'verwaltung_lager-bearbeiten')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editLagerAction(Request $request, int $lagerid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        return $this->render('verwaltung/lager_bearbeiten.html.twig');
    }

    /**
     * @param Request $request
     * @param int $lieferantenid
     * @return Response
     */
    #[Route('/verwaltung/lager-loeschen/{lagerid}', name: 'verwaltung_lager-loeschen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteLagerAction(Request $request, int $lagerid): Response
    {
        // $user = \Pimcore\Model\DataObject\Customer::getById($lagerid);
        // $user->delete();

        return $this->redirectToRoute("verwaltung_lager");
    }

}
