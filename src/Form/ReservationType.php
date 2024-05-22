<?php

namespace App\Form;

use App\Entity\Date;
use App\Entity\Reservation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pruduct')
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
            ->add('is_status')
            ->add('validation_at', null, [
                'widget' => 'single_text',
            ])
            ->add('cancel_at', null, [
                'widget' => 'single_text',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('date', EntityType::class, [
                'class' => Date::class,
                'choice_label' => 'id',
            ])
            ->add('farmer', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
