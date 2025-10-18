<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Club;
use App\Entity\TypeClub;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du club',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => ['rows' => 5],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image du club',
                'mapped' => false,
                'required' => false, // Rendre l'image optionnelle
                'attr' => ['accept' => 'image/*'],
            ])
            ->add('dateCreation', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de création',
                'required' => false, // Rendre la date optionnelle pour que la date actuelle soit utilisée si vide
                'data' => new \DateTime(), // Utilise la date actuelle si aucune date n'est fournie
            ])
            ->add('type', EntityType::class, [
                'class' => TypeClub::class,
                'choice_label' => 'categorieClub',
                'label' => 'Type du club',
                'placeholder' => 'Sélectionnez un type',
                'required' => true,
            ])
            ->add('placesDisponibles', IntegerType::class, [
                'label' => 'Nombre de places disponibles',
                'attr' => ['class' => 'form-control']
            ]);
            
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
