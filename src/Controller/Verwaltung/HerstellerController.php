<?php

namespace App\Controller\Verwaltung;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HerstellerController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/verwaltung/hersteller', name: 'verwaltung_hersteller')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function herstellerAction(Request $request): Response
    {
        return $this->render('verwaltung/hersteller.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/verwaltung/hersteller-anlegen', name: 'verwaltung_hersteller-anlegen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createHerstellerAction(Request $request): Response
    {
        return $this->render('verwaltung/hersteller_anlegen.html.twig');
    }

    /**
     * @param Request $request
     * @param int $herstellerid
     * @return Response
     */
    #[Route('/verwaltung/hersteller-bearbeiten/{herstellerid}', name: 'verwaltung_hersteller-bearbeiten')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editHerstellerAction(Request $request, int $herstellerid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        return $this->render('verwaltung/hersteller_bearbeiten.html.twig');
    }

    /**
     * @param Request $request
     * @param int $herstellerid
     * @return Response
     */
    #[Route('/verwaltung/hersteller-loeschen/{herstellerid}', name: 'verwaltung_hersteller-loeschen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteHerstellerAction(Request $request, int $herstellerid): Response
    {
        $user = \Pimcore\Model\DataObject\Customer::getById($herstellerid);
        // $user->delete();

        return $this->redirectToRoute("verwaltung_hersteller");
    }

}
