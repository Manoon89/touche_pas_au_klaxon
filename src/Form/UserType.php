<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\CallbackTransformer;

class UserType extends AbstractType
{
    /* Not used for now
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => ['maxlength' => 40],
                'help' => 'Maximum 40 caractères, uniquement des lettres, espaces ou tirets',                
                'constraints' => [
                    new Assert\Length(['max' => 40]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z\s\-]+$/',
                        'message' => 'Le nom ne peut contenir que des lettres, espaces ou tirets'
                    ]),
                ],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['maxlength' => 20],
                'help' => 'Maximum 20 caractères, uniquement des lettres, espaces ou tirets',
                'constraints' => [
                    new Assert\Length(['max' => 20]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z\s\-]+$/',
                        'message' => 'Le prénom ne peut contenir que des lettres, espaces ou tirets'
                    ]),
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => ['maxlength' => 10],
                'help' => 'Le n° de téléphone doit être composé d\'exactement 10 chiffres',
                'constraints' => [
                    new Assert\Length(['max' => 10, 'min' => 10]),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'Le numéro de téléphone doit contenir uniquement des chiffres.'
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['maxlength' => 70],
                'help' => 'Exemple : utilisateur@domaine.com, maximum 70 caractères',
                'constraints' => [
                    new Assert\Length(['max' => 70]),
                    new Assert\Email([
                        'message' => 'L\'adresse email "{{ value }}" n\'est pas valide.',
                    ]),
                ],
            ])
            ->add('password')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN', 
                    'User' => 'ROLE_USER',
                ],
                'multiple' => false,
                'expanded' => true,
            ])
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($roleString) {
                    return $roleString ? [$roleString] : [];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
        */
}