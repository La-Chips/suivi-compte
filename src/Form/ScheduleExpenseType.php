<?php

namespace App\Form;

use App\Entity\ScheduleExpense;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType; 
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Add this line
use App\Entity\Categorie; // Add this line
use App\Entity\ScheduleRepeat; // Add this line
use App\Entity\BankAccount; // Add this line
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ScheduleExpenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label')
            ->add('amount',MoneyType::class,[
                'label' => 'Montant',
            ])
            ->add('category', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'libelle',
                'label' => 'Catégorie',
            ])
            ->add('scheduleRepeat', EntityType::class, [
                'class' => ScheduleRepeat::class,
                'choice_label' => 'label',
                'label' => 'Répétition',

            ])
            ->add('bankAccount',EntityType::class,[
                'class' => 'App\Entity\BankAccount',
                'choice_label' => 'label',
                'label' => 'Compte bancaire',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('b')
                        ->where('b.user = :user')
                        ->setParameter('user', $options['user_id'])
                        ->orderBy('b.label', 'ASC');
                },
            ])
            ->add('startDate',DateType::class,[
                'label' => 'Date de début',
                'widget' => 'single_text',

                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,
            
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ScheduleExpense::class,
            'user_id' => null,
        ]);
    }
}
