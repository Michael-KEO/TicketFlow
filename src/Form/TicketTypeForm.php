<?php

namespace App\Form;

use App\Entity\Ticket;
use App\Entity\Projet;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketTypeForm extends AbstractType
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit'] ?? false;
        $role = $options['user_role'] ?? null;

        $builder
            ->add('titreTicket', TextType::class, [
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Ex: Erreur 404 sur la page de connexion']
            ])
            ->add('typeTicket', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Bug' => 'Bug',
                    'Evolution' => 'Evolution',
                    'Maintenance' => 'Maintenance',
                ],
            ])
            ->add('descriptionTicket', TextType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Décrivez le problème']
            ])
            ->add('prioriteTicket', ChoiceType::class, [
                'label' => 'Priorité',
                'choices' => [
                    'Basse' => 'Basse',
                    'Normale' => 'Normale',
                    'Haute' => 'Haute',
                    'Critique' => 'Critique',
                ],
            ])
            ->add('dateEcheance', DateType::class, [
                'label' => 'Date d\'échéance',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('projet', EntityType::class, [
                'class' => Projet::class,
                'choice_label' => 'nomProjet',
                'label' => 'Projet associé',
            ]);

        if ($role === 'ROLE_ADMIN' || $isEdit) {
            $builder
                ->add('ticketId', TextType::class, [
                    'label' => 'ID du ticket',
                    'required' => false
                ])
                ->add('statutTicket', ChoiceType::class, [
                    'label' => 'Statut',
                    'choices' => [
                        'Nouveau' => 'Nouveau',
                        'En cours' => 'En cours',
                        'Resolu' => 'Resolu',
                        'Ferme' => 'Ferme',
                    ],
                    'required' => false
                ])
                ->add('dateCreation', DateType::class, [
                    'label' => 'Date de création',
                    'widget' => 'single_text',
                    'required' => false
                ])
                ->add('dateResolution', DateType::class, [
                    'label' => 'Date de résolution',
                    'widget' => 'single_text',
                    'required' => false
                ])
                ->add('rapporteur', EntityType::class, [
                    'class' => User::class,
                    'choice_label' => 'email',
                    'label' => 'Rapporteur',
                    'required' => false
                ])
                ->add('developpeur', EntityType::class, [
                    'class' => User::class,
                    'choices' => $this->userRepository->findUsersByRole('ROLE_DEV'),
                    'choice_label' => fn(User $user) => $user->getPrenom() . ' ' . $user->getNom(),
                    'label' => 'Développeur assigné',
                    'required' => false
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
            'is_edit' => false,
            'user_role' => null,
        ]);
    }
}
