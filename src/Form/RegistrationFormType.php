<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'Adresse mail',
        ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'label' => 'form.register.password.label',
                'label_attr' => ['class' => 'text-blue'],
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'id' => 'registration_form_email'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => "Votre mot de passe doit être long d'au moins {{ limit }} caractères",
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'invalid_message' => 'Erreur avec le mot de passe',
                'first_options' => [
                    'attr' => ['placeholder' => '',
                    'class' => 'form-control'],
                    'label' => 'Mot de passe'
                ],
                'second_options' => [
                    'attr' => ['placeholder' => '',
                    'class' => 'mt-1 form-control'],
                    'label' => 'Confirmer votre mot de passe'
                ]
            ])
            ->add('firstname', TextType::class, ['label' => 'Prénom'])
            ->add('lastname', TextType::class, ['label' => 'Nom de famille'])
            ->add('phone_number', TextType::class, ['label' => 'Numéro de téléphone'])
            ->add('job', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Choisissez un métier']
            ])
            ->add('photoFile', VichFileType::class, [
                'required' => false,
                'label' => 'Photo',
                'allow_delete'  => false, // not mandatory, default is true
                'download_uri' => false, // not mandatory, default is true
            ])
            ->add('company', TextType::class, ['label' => 'Nom de votre société', 'required' => false])
            ->add('enregistrer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
