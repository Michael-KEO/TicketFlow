<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')  
            ->add('prenom')
            ->add('nom')
            ->add('plainPassword', PasswordType::class, [
    'mapped' => false,
    'required' => true,
    'attr' => ['autocomplete' => 'new-password'],
])
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
