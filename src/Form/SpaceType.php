<?php

namespace App\Form;

use App\Entity\Space;
use App\Entity\SpaceDisponibility;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Config\VichUploaderConfig;
use Vich\UploaderBundle\Form\Type\VichFileType;

class SpaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('photosFile', VichFileType::class, [
                'allow_delete'  => true, // not mandatory, default is true
                'download_uri' => true, // not mandatory, default is true
            ])
            ->add('photos')
            ->add('surface', IntegerType::class)
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Space::class,
        ]);
    }
}
