<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\TypeFormation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Titre
            ->add('titre', TextType::class, [
                'label' => 'Titre de la formation',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            
            // Description
            ->add('description', TextType::class, [
                'label' => 'Description de la formation',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
    
            // Date de publication
            ->add('datePub', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
                'label' => 'Date de publication',
                'attr' => ['class' => 'form-control'],
            ])
        
            // Date limite
            ->add('dateLimite', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
                'label' => 'Date limite',
                'attr' => ['class' => 'form-control'],
            ])
        
            // Type de formation
            ->add('typeformation', EntityType::class, [
                'class' => TypeFormation::class,
                'choice_label' => 'libelle',
                'placeholder' => 'Choisir un type de formation',
                'label' => 'Type de formation',
            ])
            
            // Image
            ->add('image', FileType::class, [
                'label' => 'Télécharger une image',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
