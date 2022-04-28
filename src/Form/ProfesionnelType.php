<?php

namespace App\Form;

use App\Entity\Profesionnel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfesionnelType extends AbstractType
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
            ->add('raison_social', null, [
                'label' => 'Raison sociale',
                'attr' => [
                    'placeholder' => 'Raison sociale',
                ],
            ])
            ->add('contact', null, [
                'label' => 'Contact',
                'attr' => [
                    'placeholder' => 'Contact',
                ],
            ])
            ->add('mail_2', null, [
                'label' => 'Adresse mail 2',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Adresse mail 2',
                ],
            ])
            ->add('tel_1', null, [
                'label' => 'Téléphone 1',
                'attr' => [
                    'placeholder' => 'Téléphone 1',
                ],
            ])
            ->add('tel_2', null, [
                'label' => 'Téléphone 2',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Téléphone 2',
                ],
            ])
            ->add('SIRET', null, [
                'label' => 'SIRET',
                'attr' => [
                    'placeholder' => 'SIRET',
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Logo',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader un fichier de type jpeg ou png',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'Logo',
                ],
            ])
            ->add('quota', null, [
                'label' => 'Quota',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Quota',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profesionnel::class,
        ]);
    }
}
