<?php

namespace App\Controller\Verwaltung;

use App\Controller\BaseController;
use App\Model\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BenutzerController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/verwaltung/benutzer', name: 'verwaltung_beutzer')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function userAction(Request $request): Response
    {
        $this->checkPermission($this->getUser(), ["wegepate"]);

        $currentUsers = new \Pimcore\Model\DataObject\Customer\Listing();

        return $this->render('verwaltung/benutzer.html.twig', [
            'currentUsers' => $currentUsers,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/verwaltung/benutzer-anlegen', name: 'verwaltung_beutzer-anlegen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createUserAction(Request $request): Response
    {
        return $this->render('verwaltung/benutzer_anlegen.html.twig');
    }

    /**
     * @param Request $request
     * @param int $userid
     * @return Response
     */
    #[Route('/verwaltung/benutzer-bearbeiten/{userid}', name: 'verwaltung_beutzer-bearbeiten')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editUserAction(Request $request, int $userid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        return $this->render('verwaltung/benutzer_bearbeiten.html.twig');
    }

    /**
     * @param Request $request
     * @param int $userid
     * @return Response
     */
    #[Route('/verwaltung/benutzer-loeschen/{userid}', name: 'verwaltung_beutzer-loeschen')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteUserAction(Request $request, int $userid): Response
    {
        $user = \Pimcore\Model\DataObject\Customer::getById($userid);
        // $user->delete();

        return $this->render('verwaltung/benutzer.html.twig');
    }
}
