<?php
declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CustomerFormType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $object = $options['data'];
        $isEditMode = false;
        if($object->getId()){
            $isEditMode = true;
        }
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'First name',
                'label_attr' => [
                    'class' => 'form-label',
                   
                ],
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder'=>'First name',
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last name',
                'label_attr' => [
                    'class' => 'form-label',
                    
                ],
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder'=>'Last name',
                ]
            ])
            ->add('role', TextType::class, [
                'label' => 'Function',
                'label_attr' => [
                    'class' => 'form-label',
                    
                ],
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder'=>'Function',
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'EMail-Address',
                'label_attr' => [
                    'class' => 'form-label',
                   
                ],
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder'=>'EMail-Address',
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Passwort',
                'label_attr' => [
                    'class' => 'form-label',
                    
                ],
                'required' => $isEditMode?false:true,
                'attr' => [
                    'maxlength' => PasswordHasherInterface::MAX_PASSWORD_LENGTH,
                    'class' => 'form-control mb-2',
                    'placeholder'=>'Passwort',
                ]
            ])
            ->add('password-repeated', PasswordType::class, [
                'label' => 'Repeat Password',
                "mapped"=>false,
                'label_attr' => [
                    'class' => 'form-label',
            
                ],
                'required' => $isEditMode?false:true,
                'attr' => [
                    'maxlength' => PasswordHasherInterface::MAX_PASSWORD_LENGTH,
                    'class' => 'form-control mb-2',
                    'placeholder'=>'Repeat Password',
                ]
            ])
            ->add('permission_wegepate_v', CheckboxType::class, [
                'label' => 'Path mentor',
                'required' => false,
                "mapped"=>false,
                'label_attr' => [
                    'class' => 'form-check-label',
                ],
                'attr' => [
                    'class' => 'form-check-input mb-2'
                ],
                'data' => $object->getPermission_wegepate(), // Set to true to mark as checked
            ])
            ->add('permission_lagerist_v', CheckboxType::class, [
                'label' => 'Inventory manager',
                'required' => false,
                "mapped"=>false,
                'label_attr' => [
                    'class' => 'form-check-label',
                ],
                'attr' => [
                    'class' => 'form-check-input mb-2'
                ],
                'data' => $object->getPermission_lagerist(), // Set to true to mark as checked
            ])
            ->add('permission_wegemanager_v', CheckboxType::class, [
                'label' => 'Path manager',
                "mapped"=>false,
                'required' => false,
                'label_attr' => [
                    'class' => 'form-check-label',
                ],
                'attr' => [
                    'class' => 'form-check-input mb-2'
                ],
                'data' => $object->getPermission_wegemanager(), // Set to true to mark as checked
            ])
            ->add('permission_admin_v', CheckboxType::class, [
                'label' => 'Administrator',
                "mapped"=>false,
                'required' => false,
                'label_attr' => [
                    'class' => 'form-check-label',
                ],
                'attr' => [
                    'class' => 'form-check-input mb-2'
                ],
                'data' =>  $object->getPermission_admin(), // Set to true to mark as checked
            ]);
         
       

    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        // we need to set this to an empty string as we want _username as input name
        // instead of login_form[_username] to work with the form authenticator out
        // of the box
        return '';
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
