<?php

namespace App\Form;

use App\Entity\Filter;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateFilterType extends AbstractType
{
    private mixed $categories;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->categories = $options['categories'];
        $builder
            ->add('keyword',null,[
                'label' => 'Mot clé',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Mot clé',
                ],
            ])
            ->add('categorie',EntityType::class,[
                'class' => Categorie::class,
                'choice_label' => 'libelle',
                'required' => true,
                'label' => 'Catégorie',
                'attr' => ['class' => 'form-select'],
                'choices' => $this->categories,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
            'categories' => [],
        ]);
    }
}
