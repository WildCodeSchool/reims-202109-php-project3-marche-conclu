<?php

namespace App\Form;

use App\Entity\Space;
use App\Entity\SpaceDisponibility;
use Symfony\Config\VichUploaderConfig;
use Symfony\Component\Form\AbstractType;
use phpDocumentor\Reflection\Types\Integer;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SpaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nom de votre annonce'
            ])
            ->add('capacity', IntegerType::class, [
                'required' => true,
                'label' => 'Capacité'
            ])
            ->add('category', ChoiceType::class, [
                'required' => true,
                'label' => 'Catégorie',
                'choices'  => [
                    'Salle de réunion' => 'reunion',
                    'Co-working' => 'co-working',
                    'Bureau privé' => 'private',
                    'Open Space' => 'open-space',
                    'Plateaux vides' => 'plates'
                ],
            ])
            ->add('address'),
            ->add('surface', IntegerType::class, [
                'required' => true,
                'label' => 'Surface (en m²)'
            ])
            ->add('location', ChoiceType::class, [
                'required' => true,
                'label' => 'Ville',
                'choices'  => [
                    'Paris' => 'Paris',
                    'Marseille' => 'Marseille',
                    'Lyon' => 'Lyon',
                ],
            ])
            ->add('price', IntegerType::class, [
                'required' => true,
                'label' => 'Prix par jour'
            ])
            ->add('photosFile', VichFileType::class, [
                'required' => true,
                'label' => 'Photo',
                'allow_delete'  => false, // not mandatory, default is true
                'download_uri' => false, // not mandatory, default is true
            ])
            ->add('enregistrer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Space::class,
        ]);
    }
}
