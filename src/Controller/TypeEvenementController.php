<?php



namespace App\Controller;
use App\Form\TypeEvenementType;
use App\Entity\TypeEvenement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\TypeEvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TypeEvenementController extends AbstractController
{
    #[Route('/type/evenement', name: 'app_type_evenement')]
    public function index(): Response
    {
        return $this->render('type_evenement/index.html.twig', [
            'controller_name' => 'TypeEvenementController',
        ]);
    }

  

    #[Route('/typeevenement', name: 'type_evenement_affiche')]
    public function affiche(TypeEvenementRepository $repository): Response
    {
        $typeEvenements = $repository->findAll();
        return $this->render('type_evenement/affiche.html.twig', [
            'typeEvenements' => $typeEvenements,
        ]);
    }

    #[Route('/typeevenement/new', name: 'type_evenement_ajout')]
    public function ajout(Request $request, EntityManagerInterface $em): Response
    {
        $typeEvenement = new TypeEvenement();
        $form = $this->createForm(TypeEvenementType::class, $typeEvenement);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($typeEvenement);
            $em->flush();

            return $this->redirectToRoute('type_evenement_affiche');
        }

        return $this->render('type_evenement/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/typeevenement/{id}/edit', name: 'type_evenement_modifier')]
    public function modifier(Request $request, TypeEvenement $typeEvenement, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TypeEvenementType::class, $typeEvenement);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('type_evenement_affiche');
        }

        return $this->render('type_evenement/modifier.html.twig', [
            'form' => $form->createView(),
            'typeEvenement' => $typeEvenement,
        ]);
    }

    #[Route('/typeevenement/{id}/delete', name: 'type_evenement_supprimer', methods: ['POST'])]
    public function supprimer(Request $request, TypeEvenement $typeEvenement, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $typeEvenement->getId(), $request->request->get('_token'))) {
            $em->remove($typeEvenement);
            $em->flush();
        }

        return $this->redirectToRoute('type_evenement_affiche');
    }

    #[Route('/typeevenement/{id}', name: 'type_evenement_afficher')]
    public function afficher(TypeEvenement $typeEvenement): Response
    {
        return $this->render('type_evenement/afficher.html.twig', [
            'typeEvenement' => $typeEvenement,
        ]);
    }



























}
