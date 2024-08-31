<?php

namespace App\Form;

use App\Entity\BankAccount;
use App\Entity\Categorie;
use App\Entity\Ligne;
use App\Entity\Statut;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class LigneType extends AbstractType
{
    private mixed $categories = [];
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => $options['date'],
                'label' => 'Date de la transaction',

                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => true,

                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('libelle', null, [
                'label' => 'Libellé',
                'attr' => array(
                    'placeholder' => 'Boulangerie, Carrefour, ...'
                ),
            ])

            ->add('montant',MoneyType::class)
            ->add('type',null, [
                'data' => $options['type'],
                'label' => 'Type de transaction',
                'attr' => array(
                    'placeholder' => 'CB, Virement, Espèces, ...'
                )
            ])
            ->add('bankAccount', EntityType::class, array(
                'class' => BankAccount::class,
                'choice_label' => 'label',
                'attr' => array(
                    'class' => 'form-select'
                ),
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('u')
                        ->where('u.user = :user')
                        ->setParameter('user', $options['user_id']);
                },

            ))
            ->add('categorie',EntityType::class, array(
                'class' => Categorie::class,
                'choice_label' => 'libelle',
                'label' => 'Catégorie',
                'choices' => $options['categories'],
                'attr' => array(
                    'class' => 'form-select'
                )
            ))
            // ->add('origine', ChoiceType::class, array(
            //     'choices' => array(
            //         'Manuel' => 0,
            //         'Automatique' => 1,
            //     ),
            //     'data' => 0,
            //     'label' => 'Origine',
            // ))

            ->add('owner', null, [
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('u')
                        ->where('u.id <> :id')
                        ->setParameter('id', $options['user_id']);
                },
                'data' => $options['shared_with'],
                'label' => 'Participants',
                'mapped' => false,
                'expanded' => true,
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ligne::class,
            'date' => new \DateTime('now', new \DateTimeZone('Europe/paris')),
            'statut' => null,
            'categories' => null,
            'type'=> null,
            'user_id' => 0,
            'shared_with' => [],
        ]);
    }
}