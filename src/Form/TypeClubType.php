<?php

namespace App\Form;

use App\Entity\TypeClub;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TypeClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categorieClub', TextType::class, [
                'label' => 'Catégorie de Club',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 15, // Limite le nombre maximal de caractères à 15
                    'minlength' => 2,  // Limite le nombre minimal de caractères à 2
                    'placeholder' => 'Entrez le nom de la catégorie (2-15 caractères)',
                ],
            ])
            ->add('descriptionCategClub', TextType::class, [
                'label' => 'Description de la Catégorie',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 50, // Limite le nombre maximal de caractères à 50
                    'minlength' => 5,  // Limite le nombre minimal de caractères à 5
                    'placeholder' => 'Entrez une description (5-50 caractères)',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TypeClub::class,
        ]);
    }
}
