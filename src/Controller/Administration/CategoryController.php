<?php

namespace App\Controller\Administration;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Pimcore\Model\DataObject\Category;
use Pimcore\Model\DataObject;
use App\Services\CommonFormBuilder;

class CategoryController extends BaseController implements BaseAdministrationController
{

    private $moduleName = 'Category';
    private $parentPath = '/Categories';

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/category', name: 'administration_category')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function categoryAction(Request $request): Response
    {
        $parent = DataObject\Service::createFolderByPath($this->parentPath);
        $categories = new Category\Listing();
        $categories->setCondition("parentId = ?", [$parent->getId()]);
        $parentCategories = [];
        foreach ($categories as $key => $value) {
            $childCategories = new Category\Listing();
            $childCategories->setCondition("parentId = ?", [$value->getId()]);
            $parentCategories[] = [
                'id' => $value->getId(),
                'name' => $value->getName(),
                'parentId' => $value->getParentId(),
                'childCategories' => $childCategories->load()
            ];
        }

        return $this->render('administration/category.html.twig', [
            'parentCategories' => $parentCategories,
            'parent' => $parent
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/category-create/{parentId}', name: 'administration_category-create')]
    #[Route('/scadmin/category-edit/{parentId}/{id}', name: 'administration_category-edit')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createCategoryAction(Request $request, CommonFormBuilder $commonFormBuilder, $parentId = null , $id = null): Response
    {

        if ($id) {
            $object = Category::getById($id);
           
            if(empty($object)){
                throw $this->createNotFoundException($this->moduleName.' is not found');
            }
        }else{
            $object = new Category();
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
                $object->setParentId($parentId);
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
                        return $this->redirectToRoute('administration_category-create', ['parentId'=>$parentId ]);
                    } else{
                        return $this->redirectToRoute('administration_category-edit', ['id'=>$id,'parentId'=>$parentId ]);
                    }
               
            }

            return $this->redirectToRoute('administration_category');

        }

        return $this->render('administration/category_create.html.twig',[
            'form' => $form->createView(),
            'id' => $id
        ]);
    }

    

    /**
     * @param Request $request
     * @param int $kategorieid
     * @return Response
     */
    #[Route('/scadmin/category-delete/{id}', name: 'administration_category-delete')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteCategoryAction(Request $request, int $id): Response
    {
        // $user = \Pimcore\Model\DataObject\Customer::getById($kategorieid);
        // $user->delete();
        $category = Category::getById($id);
        $category->delete();
        $session = $request->getSession();
        $session->getFlashBag()->set('success', $this->moduleName.' has been deleted successfully');

        return $this->redirectToRoute("administration_category");
    }
}
