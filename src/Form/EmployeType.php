<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('super')
            ->add('password')
            ->add('matricule')
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('telPro')
            ->add('telPerso')
            ->add('dateEmbauche')
            ->add('userRoles')
            ->add('groups')
            ->add('poste')
            ->add('contactUrgence')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
