<?php

namespace App\Controller\Administration;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ManufacturerController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/manufacturer', name: 'administration_manufacturer')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function manufacturerAction(Request $request): Response
    {
        return $this->render('administration/manufacturer.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/manufacturer-create', name: 'administration_manufacturer-create')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createManufacturerAction(Request $request): Response
    {
        return $this->render('administration/manufacturer_create.html.twig');
    }

    /**
     * @param Request $request
     * @param int $herstellerid
     * @return Response
     */
    #[Route('/scadmin/manufacturer-edit/{herstellerid}', name: 'administration_manufacturer-edit')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editManufacturerAction(Request $request, int $herstellerid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        return $this->render('administration/manufacturer_edit.html.twig');
    }

    /**
     * @param Request $request
     * @param int $herstellerid
     * @return Response
     */
    #[Route('/scadmin/manufacturer-delete/{herstellerid}', name: 'administration_manufacturer-delete')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteManufacturerAction(Request $request, int $herstellerid): Response
    {

        return $this->redirectToRoute("administration_manufacturer");
    }

}
