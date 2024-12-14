<?php

namespace App\Controller\Administration;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Pimcore\Model\DataObject\Warehouse;
use Pimcore\Model\DataObject;
use App\Services\CommonFormBuilder;

class WarehouseController extends BaseController implements BaseAdministrationController
{

    private $parentPath = '/Warehouses';
    private $moduleName = 'Warehouse';

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/warehouse', name: 'administration_warehouse')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function warehouseAction(Request $request): Response
    {
        $warehouseList = new Warehouse\Listing();

        return $this->render('administration/warehouse.html.twig',[
            'warehouseList' => $warehouseList
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/warehouse-edit/{lagerid}', name: 'administration_warehouse-edit')]
    #[Route('/scadmin/warehouse-create', name: 'administration_warehouse-create')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createWarehouseAction(Request $request, CommonFormBuilder $commonFormBuilder, $lagerid= null): Response
    {
        

        if ($lagerid) {
            $warehouse = Warehouse::getById($lagerid);
           
            if(empty($warehouse)){
                throw $this->createNotFoundException('Warehouse is not found');
            }
        }else{
            $warehouse = new Warehouse();
        }
        
        $form = $this->createFormBuilder($warehouse);
        $commonFormBuilder->build($form,$warehouse);
      
        $form = $form->getForm();
        // Handle the request
        $form->handleRequest($request);
        
        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $warehouse = $form->getData();
            
            if($lagerid == null){
                $validKey  = \Pimcore\Model\Element\Service::getValidKey($warehouse->getName(),'object');
                $warehouse->setKey($validKey);
                $warehouse->setPublished(true);
                $parent = DataObject\Service::createFolderByPath($this->parentPath);
                $warehouse->setParent($parent);
            }

            $session = $request->getSession();

            try{
                $warehouse->save();
                if($lagerid){
                    $session->getFlashBag()->set('success', 'Warehouse has been updated successfully');
                }else{
                    $session->getFlashBag()->set('success', 'Warehouse has been created successfully');
                }

            }catch(
                \Exception  $e){
                    $session->getFlashBag()->set('danger',  $e->getMessage());
                    if($lagerid == null){
                        return $this->redirectToRoute('administration_warehouse-create');
                    } else{
                        return $this->redirectToRoute('administration_warehouse-edit', ['lagerid'=>$lagerid]);
                    }
               
            }

            return $this->redirectToRoute('administration_warehouse');

        }

        return $this->render('administration/warehouse_create.html.twig',
                    [
                        'form' => $form->createView(),
                        'id' => $lagerid
                    ]);
    }


    /**
     * @param Request $request
     * @param int $lieferantenid
     * @return Response
     */
    #[Route('/scadmin/warehouse-delete/{id}', name: 'administration_warehouse-delete')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteWarehouseAction(Request $request, int $id): Response
    {
        // $user = \Pimcore\Model\DataObject\Customer::getById($lagerid);
        // $user->delete();

        $warehouse = Warehouse::getById($id);
        $warehouse->delete();
        $session = $request->getSession();
        $session->getFlashBag()->set('success', $this->moduleName.' has been deleted successfully');
        
        return $this->redirectToRoute("administration_warehouse");
    }

}
