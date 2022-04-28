<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Ligne;
use App\Entity\Statut;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class LigneType extends AbstractType
{
    private mixed $categories = [];
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'data' => $options['date'],

                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => true,

                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('libelle')
            ->add('libelle_2')
            ->add('montant')
            ->add('type')
            ->add('statut', EntityType::class, array(
                'class' => Statut::class,
                'choice_label' => 'libelle',
                'data' => $options['statut'],
                'attr' => array(
                    'class' => 'form-select'
                )
            ))
            ->add('categorie',EntityType::class, array(
                'class' => Categorie::class,
                'choice_label' => 'libelle',
                'data' => $options['categories'],
                'empty_data' => null,
                'attr' => array(
                    'class' => 'form-select'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ligne::class,
            'date' => new \DateTime('now', new \DateTimeZone('Europe/paris')),
            'statut' => null,
            'categories' => null,
        ]);
    }
}