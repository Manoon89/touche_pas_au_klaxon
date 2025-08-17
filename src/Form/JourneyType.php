<?php

namespace App\Form;

use App\Entity\Agency;
use App\Entity\Journey;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JourneyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departureDate')
            ->add('arrivalDate')
            ->add('totalSeats')
            ->add('availableSeats')
            ->add('departureAgency', EntityType::class, [
                'class' => Agency::class,
                'choice_label' => 'city',
            ])
            ->add('arrivalAgency', EntityType::class, [
                'class' => Agency::class,
                'choice_label' => 'city',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Journey::class,
        ]);
    }
}
