<?php

namespace App\Form;

use App\Entity\Space;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('location', ChoiceType::class, [
                'choices'  => [
                    'Paris' => 'Paris',
                    'Marseille' => 'Marseille',
                    'Lyon' => 'Lyon',
                ],
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Space::class,
            'csrf_protection' => false
        ]);
    }
}
