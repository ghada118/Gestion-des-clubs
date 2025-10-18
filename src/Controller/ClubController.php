<?php

namespace App\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Repository\ClubRepository;
use App\Entity\Club;
use App\Form\ClubType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Entity\DemandeInscription;
use App\Entity\Notification;
use App\Repository\DemandeInscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;



class ClubController extends AbstractController
{
    #[Route('/club', name: 'app_club')]
    public function index(): Response
    {
        return $this->render('club/index.html.twig', [
            'controller_name' => 'ClubController',
        ]);
    }


      //affichage 
      #[Route('/afficheClub', name: 'app_afficheClub', methods: ['GET'])]
      public function afficheClub(ClubRepository $repo): Response
      {
          $clubs = $repo->findAll();
      
          return $this->render('club/afficheClub.html.twig', [
              'clubs' => $clubs,
          ]);
      }
      

     #[Route('/ajoutclub', name: 'app_ajoutclub', methods: ['GET', 'POST'])]
     public function ajoutClub(ManagerRegistry $doctrine, Request $request): Response
    {
        $club = new Club();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si la date de création est null
            if (!$club->getDateCreation()) {
                // Attribuer la date d'aujourd'hui par défaut
                $club->setDateCreation(new \DateTime());
                // Ajouter un message d'alerte
                $this->addFlash('warning', 'Le système a attribué la date d\'aujourd\'hui comme date de création par défaut.');
            }

            // Gérer l'upload de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'), // Chemin défini dans services.yaml
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'image.');
                    return $this->redirectToRoute('app_ajoutclub');
                }

                $club->setImage($newFilename);
            }

            // Fixer le nombre de places disponibles à 10
            $club->setPlacesDisponibles(10);

            // Enregistrer le club en base de données
            $em = $doctrine->getManager();
            $em->persist($club);
            $em->flush();

            $this->addFlash('success', 'Club ajouté avec succès.');
            return $this->redirectToRoute('app_afficheClub');
        }

        return $this->render('club/ajoutclub.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     

    //Supprimer
    #[Route('/deleteClub', name: 'app_deleteClub', methods: ['POST'])]
    public function deleteClub(ManagerRegistry $doctrine, Request $request, ClubRepository $repo): RedirectResponse
    {
        // Récupérer l'ID du club depuis les données POST
        $id = $request->request->get('id');
    
        // Chercher le club par son ID
        $club = $repo->find($id);
    
        if ($club) {
            // Supprimer le club de la base de données
            $em = $doctrine->getManager();
            $em->remove($club);
            $em->flush();
    
        
            // Ajouter un message flash de succès
            $this->addFlash('success', 'Le club a été supprimé avec succès.');
        } else {
            // Ajouter un message flash d'erreur si le club n'est pas trouvé
            $this->addFlash('error', 'Le club n\'existe pas.');
        }
    
        // Rediriger vers la page de liste des clubs après suppression
        return $this->redirectToRoute('app_afficheClub');
    }
    




    #[Route('/editClub/{id}', name: 'app_editClub_form', methods: ['GET', 'POST'])]
    public function editClub(int $id, ClubRepository $repo, ManagerRegistry $doctrine, Request $request): Response
    {
        // Récupérer le club par ID
        $club = $repo->find($id);
    
        if (!$club) {
            $this->addFlash('error', 'Le club demandé n\'existe pas.');
            return $this->redirectToRoute('app_afficheClub');
        }
    
        // Créer et manipuler le formulaire
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si une nouvelle image a été téléchargée
            $imageFile = $form->get('image')->getData();
            
            if ($imageFile) {
                // Créer un nom unique pour le fichier image
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                
                try {
                    // Déplacer l'image vers le dossier de destination
                    $imageFile->move(
                        $this->getParameter('images_directory'), // Chemin d'upload défini dans les paramètres
                        $newFilename
                    );
                    
                    // Mettre à jour le chemin de l'image dans l'entité
                    $club->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'image.');
                    return $this->redirectToRoute('app_editClub_form', ['id' => $id]);
                }
            }
    
            // Sauvegarder les modifications dans la base de données
            $em = $doctrine->getManager();
            $em->flush();
    
            $this->addFlash('success', 'Le club a été modifié avec succès.');
    
            return $this->redirectToRoute('app_afficheClub');
        }
    
        return $this->render('club/editClub.html.twig', [
            'form' => $form->createView(),
            'club' => $club,

            
        ]);
    }




    #[Route('/clubs/demandes', name: 'app_demandes_clubs', methods: ['GET'])]
    public function afficherToutesLesDemandes(DemandeInscriptionRepository $demandeInscriptionRepository): Response
    {
        // Récupérer les demandes avec statut "en attente"
        $demandes = $demandeInscriptionRepository->findBy(['statut' => 'en attente']);
    
        return $this->render('club/demandes.html.twig', [
            'demandes' => $demandes, // Passez les demandes au template
        ]);
    }


    #[Route('/club/{idClub}/demande/{idDemande}/{statut}', name: 'app_traiter_demande', methods: ['POST', 'GET'])]
    public function traiterDemande(
        int $idClub,
        int $idDemande,
        string $statut,
        EntityManagerInterface $entityManager,
        DemandeInscriptionRepository $demandeInscriptionRepository
    ): Response {
        if (!in_array($statut, ['acceptée', 'refusée'], true)) {
            throw $this->createNotFoundException('Statut de la demande invalide.');
        }

        $demande = $demandeInscriptionRepository->find($idDemande);
        if (!$demande || $demande->getClub()->getId() !== $idClub) {
            throw $this->createNotFoundException('La demande n\'existe pas ou ne correspond pas à ce club.');
        }

        $demande->setStatut($statut);
        $entityManager->persist($demande);

        if ($statut === 'acceptée') {
            $club = $demande->getClub();
            $etudiant = $demande->getEtudiant();

            if ($club->getPlacesDisponibles() > 0) {
                $club->addEtudiant($etudiant);
                $club->setPlacesDisponibles($club->getPlacesDisponibles() - 1);
                $entityManager->persist($club);

                $this->addFlash('success', 'La demande a été acceptée et l\'étudiant ajouté au club.');
            } else {
                $this->addFlash('error', 'Le club n\'a plus de places disponibles.');
            }
        } else {
            $this->addFlash('info', 'La demande a été refusée.');
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_demandes_clubs');
    }

}