<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\TypeEvenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Doctrine\ORM\Mapping as ORM;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        //->add('titreE')
        // ->add('dateE')
        // ->add('descriptionE')


        ->add('titreE', TextType::class,[
            'label' => 'Titre',
            'required' => true,
        ])

        ->add('descriptionE', TextType::class, [
         'label' => 'Description',
         'required' => true,
     ])


        // ->add('typeE')


        ->add('typeE', TextType::class, [
            'label' => 'Type personnalisé',
            'required' => true,
        ])




        
         ->add('dateE', DateType::class, [
             'widget' => 'single_text', // Permet d'afficher un seul champ de saisie pour la date
             'format' => 'yyyy-MM-dd',  // Format de la date
         ])


         ->add('typeEvenement', EntityType::class, [
            'class' => TypeEvenement::class,  
            'choice_label' => 'libelle', // Afficher le champ "libelle" pour chaque type d'événement
            'placeholder' => 'Choisir un type d\'événement', // Optionnel : une option vide pour sélectionner un type
        ])

        ->add('image', FileType::class, [
            'label' => 'Image de l\'événement (JPG, PNG, etc.)',
            'mapped' => false, // Non lié directement à l'entité
            'required' => false,
            'attr' => ['class' => 'form-control']
        ])



         ->add('save',SubmitType::class)     
         ->add('reset',ResetType::class)
         ;
 }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
