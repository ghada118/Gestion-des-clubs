<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\TypeClub;
use App\Form\TypeClubType;
use App\Repository\TypeClubRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeClubController extends AbstractController
{
    #[Route('/typeclub', name: 'app_type_club')]
    public function index(): Response
    {
        return $this->render('type_club/index.html.twig', [
            'controller_name' => 'TypeClubController',
        ]);
    }

    #[Route('/afficherTypeClub', name: 'app_afficher_type_club', methods: ['GET'])]
    public function afficherTypeClub(TypeClubRepository $repository): Response
    {
        $typeClubs = $repository->findAll();

        return $this->render('type_club/afficherTypeClub.html.twig', [
            'typeClubs' => $typeClubs,
        ]);
    }

    #[Route('/ajoutTypeClub', name: 'app_ajout_type_club', methods: ['GET', 'POST'])]
    public function ajoutTypeClub(Request $request, ManagerRegistry $doctrine): Response
    {
        $typeClub = new TypeClub();
        $form = $this->createForm(TypeClubType::class, $typeClub);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($typeClub);
            $entityManager->flush();

            return $this->redirectToRoute('app_afficher_type_club');
        }

        return $this->render('type_club/ajoutTypeClub.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    
    #[Route('/editTypeClub/{id}', name: 'app_edit_type_club', methods: ['GET', 'POST'])]
    public function editTypeClub(Request $request, TypeClub $typeClub, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(TypeClubType::class, $typeClub);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('app_afficher_type_club');
        }

        return $this->render('type_club/editTypeClub.html.twig', [
            'form' => $form->createView(),
            'typeClub' => $typeClub,
        ]);
    }


    // Delete TypeClub
    #[Route('/deleteTypeClub/{id}', name: 'app_delete_type_club', methods: ['POST'])]
    public function deleteTypeClub(Request $request, TypeClub $typeClub, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete' . $typeClub->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($typeClub);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_afficher_type_club');
    }
}
