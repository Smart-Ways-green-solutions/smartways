<?php

namespace App\Services;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Pimcore\Model\DataObject\ClassDefinition\Data\Input;
use Pimcore\Model\DataObject\ClassDefinition\Data\Image;

class CommonFormBuilder
{
    

    public function build(&$form, $object){


        $classDefination = $object->getClass();
        $fields = $classDefination->getFieldDefinitions();
       
        foreach ($fields as $key => $value) {

            $mandatory = "";
            if($value->getMandatory()){
                $mandatory = " *";
            }

            if($value instanceof Input){
                $form->add($value->getName(), TextType::class, [
                    'label' => $value->getTitle().$mandatory,
                    'required' => $value->getMandatory(),
                    'attr'=>[
                        'placeholder'=>$value->getTitle()
                    ],
                    "data" => $object->{'get'.ucFirst($value->getName())}(),
                ]);
            }
        }

 


    }
}
