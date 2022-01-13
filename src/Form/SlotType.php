<?php

namespace App\Form;

use App\Entity\Slot;
use Datetime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SlotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('slotTime', DateType::class, [
                'label' => ' ',
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
                'attr' =>  array(
                    'min' => (new DateTime('+1 day'))->format('Y-m-d'),
                )
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Slot::class,
        ]);
    }
}
