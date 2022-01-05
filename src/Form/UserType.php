<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
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
                'invalid_message' => 'The passwords do not match!',
                'first_options' => [
                    'attr' => ['class' => 'form-control'],
                    'label' => 'Mot de passe'
                ],
                'second_options' => [
                    'attr' => ['class' => 'mt-1 form-control'],
                    'label' => 'Confirmer votre mot de passe'
                ]
            ])
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('avatar', TextType::class)
            ->add('company', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
