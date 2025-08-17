<?php

namespace App\Form;

use App\Entity\Agency;
use App\Entity\Journey;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class JourneyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departureDate', DateTimeType::class, [
                'label' => 'Date et heure de départ'
            ])
            ->add('arrivalDate', DateTimeType::class, [
                'label' => 'Date et heure estimée d’arrivée'
            ])
            ->add('totalSeats', IntegerType::class, [
                'label' => 'Nombre de sièges total'
            ])
            ->add('availableSeats', IntegerType::class, [
                'label' => 'Nombre de sièges disponibles'
            ])
            ->add('departureAgency', EntityType::class, [
                'class' => Agency::class,
                'choice_label' => 'city',
                'label' => 'Agence de départ'
            ])
            ->add('arrivalAgency', EntityType::class, [
                'class' => Agency::class,
                'choice_label' => 'city',
                'label' => 'Agence d’arrivée'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Journey::class,
        ]);
    }
}