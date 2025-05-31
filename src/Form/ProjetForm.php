<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Projet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('projet_id')
            ->add('nomProjet')
            ->add('descriptionProjet')
            ->add('dateDebut')
            ->add('dateFin')
            ->add('satut')
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'nomClient',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
