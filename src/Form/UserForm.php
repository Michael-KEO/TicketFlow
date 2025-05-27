<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('password')
            ->add('prenom')
            ->add('nom')
            ->add('isVerified')
            ->add('roles' , ChoiceType::class, [
        'choices' => [
            'Administrateur' => 'ROLE_ADMIN',
            'Développeur' => 'ROLE_DEV',
            'Rapporteur' => 'ROLE_RAPPORTEUR',
        ],
        'multiple' => true,
        'expanded' => true,
    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
