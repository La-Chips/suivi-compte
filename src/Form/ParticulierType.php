<?php

namespace App\Form;

use App\Entity\Particulier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticulierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mail', EmailType::class, [
                'label' => 'Adresse mail',
                'attr' => [
                    'placeholder' => 'Adresse mail',
                ],
            ])
            ->add('rue', null, [
                'label' => 'Rue',
                'attr' => [
                    'placeholder' => 'Rue',
                ],
            ])
            ->add('cp', null, [
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => 'Code postal',
                ],
            ])
            ->add('ville', null, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Ville',
                ],
            ])
            ->add('pays', null, [
                'label' => 'Pays',
                'attr' => [
                    'placeholder' => 'Pays',
                ],
            ])
            ->add('telephone', null, [
                'label' => 'Téléphone',
                'attr' => [
                    'placeholder' => 'Téléphone',
                ],
            ])
            ->add('civilite', null, [
                'label' => 'Civilité',
                'attr' => [
                    'placeholder' => 'Civilité',
                ],
            ])
            ->add('nom', null, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom',
                ],
            ])
            ->add('prenom', null, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Prénom',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Particulier::class,
        ]);
    }
}
