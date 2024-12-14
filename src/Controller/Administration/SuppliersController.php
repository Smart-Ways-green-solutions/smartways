<?php

namespace App\Controller\Administration;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Pimcore\Model\DataObject\Supplier;
use Pimcore\Model\DataObject;
use App\Services\CommonFormBuilder;

class SuppliersController extends BaseController implements BaseAdministrationController
{
    private $moduleName = 'Supplier';
    private $parentPath = '/Suppliers';
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/suppliers', name: 'administration_suppliers')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function suppliersAction(Request $request): Response
    {
        $supplierList = new Supplier\Listing();

        return $this->render('administration/suppliers.html.twig',[
            'supplierList' => $supplierList
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/suppliers-create', name: 'administration_suppliers-create')]
    #[Route('/scadmin/suppliers-edit/{lagerid}', name: 'administration_suppliers-edit')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createSuppliersAction(Request $request, CommonFormBuilder $commonFormBuilder, $lagerid= null): Response
    {
        if ($lagerid) {
            $object = Supplier::getById($lagerid);
           
            if(empty($object)){
                throw $this->createNotFoundException($this->moduleName.' is not found');
            }
        }else{
            $object = new Supplier();
        }
        
        $form = $this->createFormBuilder($object);
        $commonFormBuilder->build($form,$object);
      
        $form = $form->getForm();
        // Handle the request
        $form->handleRequest($request);
        
        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            
            if($lagerid == null){
                $validKey  = \Pimcore\Model\Element\Service::getValidKey($object->getName(),'object');
                $object->setKey($validKey);
                $object->setPublished(true);
                $parent = DataObject\Service::createFolderByPath($this->parentPath);
                $object->setParent($parent);
            }

            $session = $request->getSession();

            try{
                $object->save();
                if($lagerid){
                    $session->getFlashBag()->set('success', $this->moduleName.' has been updated successfully');
                }else{
                    $session->getFlashBag()->set('success', $this->moduleName.' has been created successfully');
                }

            }catch(
                \Exception  $e){
                    $session->getFlashBag()->set('danger',  $e->getMessage());
                    if($lagerid == null){
                        return $this->redirectToRoute('administration_suppliers-create');
                    } else{
                        return $this->redirectToRoute('administration_suppliers-edit', ['lagerid'=>$lagerid]);
                    }
               
            }

            return $this->redirectToRoute('administration_suppliers');

        }

        return $this->render('administration/suppliers_create.html.twig',
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
    #[Route('/scadmin/suppliers-delete/{id}', name: 'administration_suppliers-delete')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteSuppliersAction(Request $request, int $id): Response
    {

        $supplier = Supplier::getById($id);
        $supplier->delete();
        $session = $request->getSession();
        $session->getFlashBag()->set('success', $this->moduleName.' has been deleted successfully');

        return $this->redirectToRoute("administration_suppliers");
    }

}
