<?php

// src/Controller/FormationController.php
namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;

class FormationController extends AbstractController
{
    #[Route('/formation', name: 'app_formation')]
    public function index(FormationRepository $repository): Response
    {
        $totalFormations = $repository->countAllFormations(); // Utiliser la méthode de repository pour obtenir le nombre total de formations

        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
            'totalFormations' => $totalFormations, 
        ]);
    }

    #[Route('/affiche_formation', name: 'app_affiche_formation')]
    public function affiche_formation(FormationRepository $rep): Response
    {
        $formations = $rep->findAll();
        return $this->render('formation/affiche_formation.html.twig', [
            'formations' => $formations,
        ]);
    }

    #[Route('/ajout_formation', name: 'app_ajout_formation')]
    public function ajout_formation(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $datePub = $form->get('datePub')->getData();
            $dateLimite = $form->get('dateLimite')->getData();

            if ($dateLimite < $datePub) {
                $form->get('dateLimite')->addError(new FormError('La date limite doit être après la date de publication.'));
            }

            if ($form->isValid()) {
                $imageFile = $form->get('image')->getData();
                if ($imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                    $imageFile->move($this->getParameter('uploads_directory'), $newFilename);
                    $formation->setImage($newFilename);
                }

                $em = $doctrine->getManager();
                $em->persist($formation);
                $em->flush();

                return $this->redirectToRoute('app_affiche_formation');
            }
        }

        return $this->render('formation/ajout_formation.html.twig', [
            'f' => $form,
        ]);
    }

    #[Route('/formation/supprimer/{id}', name: 'formation_supprimer')]
    public function supprimer(ManagerRegistry $doctrine, int $id): RedirectResponse
    {
        $entityManager = $doctrine->getManager();
        $formation = $entityManager->getRepository(Formation::class)->find($id);

        if ($formation) {
            $entityManager->remove($formation);
            $entityManager->flush();
            $this->addFlash('success', 'Formation supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Formation introuvable.');
        }

        return $this->redirectToRoute('app_affiche_formation');
    }

    #[Route('/formation/modifier/{id}', name: 'Modifier_formation')]
    public function modifier(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $formation = $entityManager->getRepository(Formation::class)->find($id);

        if (!$formation) {
            $this->addFlash('error', 'Formation introuvable.');
            return $this->redirectToRoute('app_affiche_formation');
        }

        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );

                if ($formation->getImage()) {
                    $oldFilePath = $this->getParameter('uploads_directory') . '/' . $formation->getImage();
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $formation->setImage($newFilename);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Formation modifiée avec succès.');
            return $this->redirectToRoute('app_affiche_formation');
        }

        return $this->render('formation/modifier_formation.html.twig', [
            'form' => $form->createView(),
            'formation' => $formation,
        ]);
    }
    
    #[Route('/search', name: 'app_search_formation')]
    public function search(Request $request, FormationRepository $repository): Response
    {
        $title = $request->query->get('title');
        $formations = $title ? $repository->findByTitle($title) : [];
    
        return $this->render('formation/affiche_formation.html.twig', [
            'formations' => $formations,
        ]);
    }

 
}
