<?php

namespace App\Controller\Administration;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SuppliersController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/suppliers', name: 'administration_suppliers')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function suppliersAction(Request $request): Response
    {
        return $this->render('administration/suppliers.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/suppliers-create', name: 'administration_suppliers-create')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createSuppliersAction(Request $request): Response
    {
        return $this->render('administration/suppliers_create.html.twig');
    }

    /**
     * @param Request $request
     * @param int $lieferantenid
     * @return Response
     */
    #[Route('/scadmin/suppliers-edit/{lieferantenid}', name: 'administration_suppliers-edit')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editSuppliersAction(Request $request, int $lieferantenid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        return $this->render('administration/suppliers_edit.html.twig');
    }

    /**
     * @param Request $request
     * @param int $lieferantenid
     * @return Response
     */
    #[Route('/scadmin/suppliers-delete/{lieferantenid}', name: 'administration_suppliers-delete')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteSuppliersAction(Request $request, int $lieferantenid): Response
    {

        return $this->redirectToRoute("administration_suppliers");
    }

}
