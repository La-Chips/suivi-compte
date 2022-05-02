<?php

namespace App\Form;

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

class LigneType extends AbstractType
{
    private mixed $categories = [];
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => $options['date'],
                'label' => 'Date de la transction',

                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => true,

                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('libelle', null, [
                'label' => 'Libellé',
            ])
            ->add('libelle_2', null, [
                'label' => 'Libellé 2',
            ])
            ->add('montant')
            ->add('type',null, [
                'data' => $options['type'],
                'label' => 'Type de transaction',
            ])
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
                'label' => 'Catégorie',
                'choices' => $options['categories'],
                'attr' => array(
                    'class' => 'form-select'
                )
            ))
            ->add('origine', ChoiceType::class, array(
                'choices' => array(
                    'Manuel' => 0,
                    'Automatique' => 1,
                ),
                'data' => 0,
                'label' => 'Origine',
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ligne::class,
            'date' => new \DateTime('now', new \DateTimeZone('Europe/paris')),
            'statut' => null,
            'categories' => null,
            'type'=> null,
        ]);
    }
}