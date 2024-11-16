<?php

namespace App\Controller\Warehouse;

use App\Controller\BaseController;
use App\Model\Customer;
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
    #[Route('/warehouse/warehouses', name: 'warehouse')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function warehouseAction(Request $request): Response
    {
        $this->checkPermission($this->getUser(), ["wegepate"]);

        return $this->render('warehouse/warehouse.html.twig', [

        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/warehouse/inventory', name: 'warehouse-inventory')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function warehouseInventoryAction(Request $request): Response
    {
        return $this->render('');
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/warehouse/history', name: 'warehouse-history')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function warehouseHistoryAction(Request $request): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        return $this->render('warehouse/warehouse_history.html.twig');
    }


}
