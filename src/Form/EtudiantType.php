<?php

namespace App\Form;

use App\Entity\Etudiant;
use App\Entity\Club;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;  // Import du type Email

class EtudiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('email', EmailType::class, [ // Ajout du champ email
                'label' => 'Email',
                'required' => true, // Rend le champ obligatoire
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez votre adresse email',
                ],
            ])
            ->add('clubs', EntityType::class, [
                'class' => Club::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true, // Affichage sous forme de cases à cocher
                'attr' => ['class' => 'form-check'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class, // Associer le formulaire à l'entité Etudiant
        ]);
    }
}
