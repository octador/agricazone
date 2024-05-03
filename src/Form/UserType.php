<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            // ->add('roles')
            ->add('password')
            ->add('isVerified')
            ->add('lastname')
            ->add('firstname')
            ->add('adress')
            ->add('postalcode')
            ->add('city')
            ->add('phone')
            ->add('picture' ,FileType::class,[
                'label'=> 'Photo de profil',
                'mapped'=> false,
                'required'=> false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                    ])
                    ]
                ])
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
            ->add('farmer')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
