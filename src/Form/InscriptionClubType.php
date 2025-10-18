<?php

namespace App\Form;

use App\Entity\Etudiant;
use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxListType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use App\Entity\DemandeInscription;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class InscriptionClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'étudiant',
                'required' => true,
            ])
            ->add('email', EmailType::class, [  // Ajouter le champ email
                'label' => 'Email de l\'étudiant',
                'required' => true,
            ])
            ->add('clubs', EntityType::class, [
                'class' => Club::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Choisissez les clubs auxquels vous souhaitez vous inscrire',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,  // Associer le formulaire à l'entité Etudiant
        ]);
    }
}
