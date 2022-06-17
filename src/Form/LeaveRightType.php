<?php

namespace App\Form;

use App\Entity\LeaveRight;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeaveRightType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('balance')
            ->add('status')
            ->add('unit')
            ->add('startValidityDate')
            ->add('endValidityDate')
            ->add('employe')
            ->add('leaveType')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LeaveRight::class,
        ]);
    }
}
