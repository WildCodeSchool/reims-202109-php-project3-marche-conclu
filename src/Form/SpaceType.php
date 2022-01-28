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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SpaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Petit bureau..',
                ),
                'label' => 'Nom de votre annonce'
            ])
            ->add('capacity', IntegerType::class, [
                'required' => true,
                'attr' => array(
                    'placeholder' => '250',
                ),
                'label' => 'Nombre de postes disponibles'
            ])
            ->add('category', ChoiceType::class, [
                'required' => true,
                'label' => 'Catégorie',
                'choices'  => [
                    'Salle de réunion' => 'Salle de réunion',
                    'Co-working' => 'Co-working',
                    'Bureau privé' => 'Bureau privé',
                    'Open Space' => 'Open Space',
                    'Plateau vide' => 'Plateau vide'
                ],
            ])
            ->add('address', TextType::class, [
                'attr' => array(
                    'placeholder' => '6 rue de Saint-Brice',
                ),
                'label' => 'Adresse'])
            ->add('surface', IntegerType::class, [
                'required' => true,
                'attr' => array(
                    'placeholder' => '300',
                ),
                'label' => 'Surface (en m²)'
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Petit bureau orienté côté Est',
                    'maxlength' => '150',
                ),
                ])

            ->add('location', TextType::class, [
                'attr' => array(
                    'placeholder' => 'Reims',
                ),
                'required' => true,
                ])
            ->add('location', TextType::class, [
                'required' => true,
                'label' => 'Ville',
            ])
            ->add('price', IntegerType::class, [
                'required' => true,
                'attr' => array(
                    'placeholder' => '250',
                ),
                'label' => 'Prix par jour'
            ])
            // on ajoute le champs 'images' dans le formulaire
            // il n'est pas lié à la base de données (mapped à false)
            ->add('images', FileType::class, [
                'label' => 'Images à ajoutées',
                'mapped' => false,
                'multiple' => true,
                'required' => false,
            ])
            ->add('availability', TextType::class, [
                'required' => false,
                'label' => 'Disponibilités',
                'attr' => ['value' => "cliquez ici"]
            ])
            ->add('enregistrer', SubmitType::class, [
                'label' => "Poster l'annonce",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Space::class,
        ]);
    }
}
