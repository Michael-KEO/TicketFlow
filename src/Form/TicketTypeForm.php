<?php

namespace App\Form;

use App\Entity\Ticket;
use App\Entity\Client;
use App\Entity\Projet;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit'];
        $userRole = $options['user_role'];

        $builder
            ->add('titreTicket', TextType::class, [
                'label' => 'Titre du ticket',
                'constraints' => [new NotBlank(['message' => 'Le titre est obligatoire.'])],
                'attr' => ['placeholder' => 'Décrivez brièvement le problème']
            ])
            ->add('typeTicket', ChoiceType::class, [
                'label' => 'Type de ticket',
                'choices' => [
                    'Bug' => 'Bug',
                    'Évolution' => 'Évolution',
                    'Support' => 'Support',
                    'Maintenance' => 'Maintenance'
                ],
                'placeholder' => 'Sélectionnez un type'
            ])
            ->add('descriptionTicket', TextareaType::class, [
                'label' => 'Description détaillée',
                'constraints' => [new NotBlank(['message' => 'La description est obligatoire.'])],
                'attr' => [
                    'rows' => 6,
                    'placeholder' => 'Décrivez en détail le problème, les étapes pour le reproduire, le comportement attendu...'
                ]
            ])
            ->add('prioriteTicket', ChoiceType::class, [
                'label' => 'Priorité',
                'choices' => [
                    'Basse' => 'Basse',
                    'Normale' => 'Normale',
                    'Haute' => 'Haute',
                    'Critique' => 'Critique'
                ],
                'data' => 'Normale'
            ])
            ->add('dateEcheance', DateTimeType::class, [
                'label' => 'Date d\'échéance',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ]);

        // Pour les rapporteurs en création
        if (!$isEdit && $userRole === 'ROLE_RAPPORTEUR') {
            $builder
                ->add('projet', EntityType::class, [
                    'class' => Projet::class,
                    'choice_label' => function(Projet $projet) {
                        return $projet->getNomProjet() . ' (' . $projet->getClient()->getNomClient() . ')';
                    },
                    'label' => 'Projet',
                    'placeholder' => 'Sélectionnez un projet',
                    'constraints' => [new NotBlank(['message' => 'Le projet est obligatoire.'])]
                ]);
        }

        // Pour les développeurs - changement de statut
        if ($userRole === 'ROLE_DEV' || $userRole === 'ROLE_ADMIN') {
            $builder
                ->add('statutTicket', ChoiceType::class, [
                    'label' => 'Statut',
                    'choices' => [
                        'Nouveau' => 'Nouveau',
                        'Ouvert' => 'Ouvert',
                        'En cours' => 'En cours',
                        'Résolu' => 'Résolu',
                        'Fermé' => 'Fermé',
                        'Rejeté' => 'Rejeté'
                    ]
                ]);

            if ($userRole === 'ROLE_ADMIN') {
                $builder->add('developpeur', EntityType::class, [
                    'class' => User::class,
                    'choice_label' => function(User $user) {
                        return $user->getPrenom() . ' ' . $user->getNom();
                    },
                    'label' => 'Développeur assigné',
                    'placeholder' => 'Aucun développeur assigné',
                    'required' => false,
                    'query_builder' => function($repository) {
                        return $repository->createQueryBuilder('u')
                            ->andWhere('u.roles LIKE :role')
                            ->setParameter('role', '%ROLE_DEV%');
                    }
                ]);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
            'is_edit' => false,
            'user_role' => 'ROLE_USER'
        ]);
    }
}