<?php

namespace App\Controller;

use App\Entity\DemandeInscription;
use App\Entity\Etudiant;
use App\Form\InscriptionClubType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription1', name: 'inscription')]
public function inscription(
    Request $request,
    EntityManagerInterface $entityManager
): Response {
    $etudiant = new Etudiant();
    $form = $this->createForm(InscriptionClubType::class, $etudiant);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $clubsAvecPlacesDisponibles = []; // Clubs avec places disponibles
        $clubsSansPlacesDisponibles = []; // Clubs sans places disponibles

        foreach ($etudiant->getClubs() as $club) {
            if ($club->getPlacesDisponibles() > 0) {
                $clubsAvecPlacesDisponibles[] = $club;

                // Créer une nouvelle demande d'inscription
                $demande = new DemandeInscription();
                $demande->setEtudiant($etudiant);
                $demande->setClub($club);
                $demande->setStatut('en attente');
                $demande->setDateDemande(new \DateTime());

                // Réduire le nombre de places disponibles
                $club->setPlacesDisponibles($club->getPlacesDisponibles() - 1);

                $entityManager->persist($demande);
            } else {
                $clubsSansPlacesDisponibles[] = $club;
            }
        }

        if (!empty($clubsAvecPlacesDisponibles)) {
            $entityManager->persist($etudiant);
            $entityManager->flush();

            // Ajouter un message de succès pour les clubs avec places disponibles
            $this->addFlash('success', 'Vos demandes ont été soumises avec succès pour les clubs avec places disponibles.');
        }

        if (!empty($clubsSansPlacesDisponibles)) {
            // Ajouter un message d'erreur pour les clubs sans places disponibles
            foreach ($clubsSansPlacesDisponibles as $club) {
                $this->addFlash('error', sprintf(
                    'Le club "%s" n\'a pas de places disponibles.',
                    $club->getName()
                ));
            }
        }

        return $this->redirectToRoute('inscription');
    }

    return $this->render('inscription/inscription.html.twig', [
        'form' => $form->createView(),
    ]);
}

}
