<?php

namespace App\Controller\Administration;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class WarehouseController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/warehouse', name: 'administration_warehouse')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function warehouseAction(Request $request): Response
    {
        return $this->render('administration/warehouse.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/warehouse-create', name: 'administration_warehouse-create')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createWarehouseAction(Request $request): Response
    {
        return $this->render('administration/warehouse_create.html.twig');
    }

    /**
     * @param Request $request
     * @param int $lagerid
     * @return Response
     */
    #[Route('/scadmin/warehouse-edit/{lagerid}', name: 'administration_warehouse-edit')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editWarehouseAction(Request $request, int $lagerid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        return $this->render('administration/warehouse_edit.html.twig');
    }

    /**
     * @param Request $request
     * @param int $lieferantenid
     * @return Response
     */
    #[Route('/scadmin/warehouse-delete/{lagerid}', name: 'administration_warehouse-delete')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteWarehouseAction(Request $request, int $lagerid): Response
    {
        // $user = \Pimcore\Model\DataObject\Customer::getById($lagerid);
        // $user->delete();

        return $this->redirectToRoute("administration_warehouse");
    }

}
