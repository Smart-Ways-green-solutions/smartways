<?php

namespace App\Controller\Administration;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Pimcore\Model\DataObject\Tags;
use App\Services\CommonFormBuilder;
use Pimcore\Model\DataObject;
class TagsController extends BaseController implements BaseAdministrationController
{

    private $moduleName = 'Tag';
    private $parentPath = '/Tags';

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/tags', name: 'administration_tags')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function tagsAction(Request $request): Response
    {
        $tags = new Tags\Listing();

        return $this->render('administration/tags.html.twig',[
            'tags' => $tags
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/scadmin/tags-create', name: 'administration_tags-create')]
    #[Route('/scadmin/tags-edit/{id}', name: 'administration_tags-edit')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createTagsAction(Request $request, CommonFormBuilder $commonFormBuilder, $id= null): Response
    {

        if ($id) {
            $object = Tags::getById($id);
           
            if(empty($object)){
                throw $this->createNotFoundException($this->moduleName.' is not found');
            }
        }else{
            $object = new Tags();
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
                        return $this->redirectToRoute('administration_tags-create');
                    } else{
                        return $this->redirectToRoute('administration_tags-edit', ['id'=>$id]);
                    }
               
            }

            return $this->redirectToRoute('administration_tags');

        }

        return $this->render('administration/tags_create.html.twig',
        [
            'form' => $form->createView(),
            'id' => $id
        ]);
        
    }

 

    /**
     * @param Request $request
     * @param int $tagid
     * @return Response
     */
    #[Route('/scadmin/tags-delete/{id}', name: 'administration_tags-delete')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteTagsAction(Request $request, int $id): Response
    {

        $tag = Tags::getById($id);
        $tag->delete();
        $session = $request->getSession();
        $session->getFlashBag()->set('success', $this->moduleName.' has been deleted successfully');

        return $this->redirectToRoute("administration_tags");
    }

}
