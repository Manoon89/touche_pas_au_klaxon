<?php

namespace App\Form;

use App\Entity\Agency;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Formulaire pour gérer la création et l'édition d'une agence
 */
class AgencyType extends AbstractType
{
    /**
     * Configure les champs du formulaire
     * 
     * @param FormBuilderInterface $builder
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', TextType::class, [
                'label' => 'Ville', 
                'attr' => [
                    'maxlength' => 40
                ],
            'help' => "Maximum 40 caractères",
            ]);
        }

    /**
     * Configure les options du formulaire
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agency::class,
        ]);
    }
}
