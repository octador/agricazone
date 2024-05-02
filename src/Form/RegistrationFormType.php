<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('farmer', CheckboxType::class, [
                'label' => 'Je suis un agriculteur',
                'required' => false, 
            ])
            ->add('email', EmailType::class,[
                'attr' => [
                    'placeholder' => 'Email',]
            ])
            ->add('lastname', TextType::class,[
                'attr' => [
                    
                    'placeholder' => 'Nom',]
            ])
            ->add('firstname', TextType::class,[
                'attr' => [
                    'placeholder' => 'Prénom',]
            ])
            ->add('phone', TextType::class,[
                'attr' => [
                    
                    'placeholder' => 'Téléphone',]
            ])
            ->add('adress', TextType::class,[
                'attr' => [
                    
                    'placeholder' => 'Adresse',]
            ]
            )
            ->add('city', TextType::class,[
                'attr' => [
                    'autocomplete' => 'new-city',
                    'placeholder' => 'Ville',]
            ])
            ->add('postalcode', TextType::class)

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                    'placeholder' => 'Mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrée un mots de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
