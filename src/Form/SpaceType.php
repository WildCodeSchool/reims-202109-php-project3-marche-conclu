<?php

namespace App\Form;

use App\Entity\Space;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('photos')
            ->add('surface')
            ->add('location', ChoiceType::class, [
                'choices'  => [
                    'Paris' => 'Paris',
                    'Marseille' => 'Marseille',
                    'Lyon' => 'Lyon',
                ],
            ])
            ->add('capacity', IntegerType::class)
            ->add('category', ChoiceType::class, [
                'choices'  => [
                    'Espace ouvert' => 'Espace ouvert',
                    'Espace fermé' => 'Espace fermé',
                    'Espace bureau' => 'Espace bureau',
                ],
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Space::class,
        ]);
    }
}
