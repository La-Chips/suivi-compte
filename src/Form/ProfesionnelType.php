<?php

namespace App\Form;

use App\Entity\Profesionnel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfesionnelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mail')
            ->add('rue')
            ->add('cp')
            ->add('ville')
            ->add('pays')
            ->add('raison_social')
            ->add('contact')
            ->add('mail_2')
            ->add('tel_1')
            ->add('tel_2')
            ->add('SIRET')
            ->add('image')
            ->add('quota')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profesionnel::class,
        ]);
    }
}
