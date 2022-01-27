<?php

namespace App\Form;

use App\Entity\SpaceDisponibility;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SpaceDisponibilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('monday', TextType::class, [
            'attr' => ['placeholder' => '09h-17h'],
            'required' => false,
            'label' => 'Lundi'
        ])
        ->add('tuesday', TextType::class, [
            'attr' => ['placeholder' => '09h-17h'],
            'required' => false,
            'label' => 'Mardi'
        ])
        ->add('wednesday', TextType::class, [
            'attr' => ['placeholder' => '09h-17h'],
            'required' => false,
            'label' => 'Mercredi'
        ])
        ->add('thursday', TextType::class, [
            'attr' => ['placeholder' => '09h-17h'],
            'required' => false,
            'label' => 'Jeudi'
        ])
        ->add('friday', TextType::class, [
            'attr' => ['placeholder' => '09h-17h'],
            'required' => false,
            'label' => 'Vendredi'
        ])
        ->add('saturday', TextType::class, [
            'attr' => ['placeholder' => '09h-17h'],
            'required' => false,
            'label' => 'Samedi'
        ])
        ->add('sunday', TextType::class, [
            'attr' => ['placeholder' => '09h-17h'],
            'required' => false,
            'label' => 'Dimanche'
        ])
        ->add('enregistrer', SubmitType::class, [
            'label' => "Poster l'annonce",
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SpaceDisponibility::class,
        ]);
    }
}
