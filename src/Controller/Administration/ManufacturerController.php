<?php

namespace App\Controller\Administration;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Pimcore\Model\DataObject\Manufacturer;
use Pimcore\Model\DataObject;
use App\Services\CommonFormBuilder;

class ManufacturerController extends BaseController implements BaseAdministrationController
{
    private $moduleName = 'Manufacturer';
    private $parentPath = '/Manufacturers';

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/manufacturer', name: 'administration_manufacturer')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function manufacturerAction(Request $request): Response
    {
        $manufacturerList = new Manufacturer\Listing();

        return $this->render('administration/manufacturer.html.twig',[
            'manufacturerList' => $manufacturerList
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/manufacturer-create', name: 'administration_manufacturer-create')]
    #[Route('/scadmin/manufacturer-edit/{id}', name: 'administration_manufacturer-edit')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createManufacturerAction(Request $request, CommonFormBuilder $commonFormBuilder, $id= null): Response
    {

        if ($id) {
            $object = Manufacturer::getById($id);
           
            if(empty($object)){
                throw $this->createNotFoundException($this->moduleName.' is not found');
            }
        }else{
            $object = new Manufacturer();
        }
        
        $form = $this->createFormBuilder($object);
        $commonFormBuilder->build($form,$object);
      
        $form = $form->getForm();
        // Handle the request
        $form->handleRequest($request);
        
        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
     
            if($id == null){
                $validKey  = \Pimcore\Model\Element\Service::getValidKey($object->getName(),'object');
                $object->setKey($validKey);
                $object->setPublished(true);
                $parent = DataObject\Service::createFolderByPath($this->parentPath);
                $object->setParent($parent);
            }

            $session = $request->getSession();

            try{
                $object->save();
                if($id){
                    $session->getFlashBag()->set('success', $this->moduleName.' has been updated successfully');
                }else{
                    $session->getFlashBag()->set('success', $this->moduleName.' has been created successfully');
                }

            }catch(
                \Exception  $e){
                    $session->getFlashBag()->set('danger',  $e->getMessage());
                    if($id == null){
                        return $this->redirectToRoute('administration_manufacturer-create');
                    } else{
                        return $this->redirectToRoute('administration_manufacturer-edit', ['id'=>$id]);
                    }
               
            }

            return $this->redirectToRoute('administration_manufacturer');

        }

        return $this->render('administration/manufacturer_create.html.twig',[
            'form' => $form->createView(),
            'id' => $id
        ]);
    }

   

    /**
     * @param Request $request
     * @param int $herstellerid
     * @return Response
     */
    #[Route('/scadmin/manufacturer-delete/{id}', name: 'administration_manufacturer-delete')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteManufacturerAction(Request $request, int $id): Response
    {
        $manufacturer = Manufacturer::getById($id);
        $manufacturer->delete();
        $session = $request->getSession();
        $session->getFlashBag()->set('success', $this->moduleName.' has been deleted successfully');

        return $this->redirectToRoute("administration_manufacturer");
    }

}
