<?php




namespace App\Controller;

use App\Entity\TypeFormation;
use App\Repository\TypeFormationRepository;
use App\Form\TypeFormationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeFormationController extends AbstractController
{
    #[Route('/typeformation', name: 'type_formation_affiche')]
    public function affiche(TypeFormationRepository $repository): Response
    {
        $typeFormations = $repository->findAll();
        
        if (empty($typeFormations)) {
            throw $this->createNotFoundException('Aucun type de formation trouvé.');
        }

        return $this->render('type_formation/affiche.html.twig', [
            'typeFormations' => $typeFormations,
        ]);
    }

    #[Route('/typeformation/new', name: 'type_formation_ajout')]
    public function ajout(Request $request, EntityManagerInterface $em): Response
    {
        $typeFormation = new TypeFormation();
        $form = $this->createForm(TypeFormationType::class, $typeFormation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($typeFormation);
            $em->flush();

            $this->addFlash('success', 'Le type de formation a été ajouté avec succès.');
            return $this->redirectToRoute('type_formation_affiche');
        }

        return $this->render('type_formation/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/typeformation/{id}/edit', name: 'type_formation_modifier')]
    public function modifier(Request $request, TypeFormation $typeFormation, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TypeFormationType::class, $typeFormation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Le type de formation a été modifié avec succès.');
            return $this->redirectToRoute('type_formation_affiche');
        }

        return $this->render('type_formation/modifier.html.twig', [
            'form' => $form->createView(),
            'typeFormation' => $typeFormation,
        ]);
    }

    #[Route('/typeformation/{id}/delete', name: 'type_formation_supprimer', methods: ['POST'])]
    public function supprimer(Request $request, TypeFormation $typeFormation, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $typeFormation->getId(), $request->request->get('_token'))) {
            $em->remove($typeFormation);
            $em->flush();

            $this->addFlash('success', 'Le type de formation a été supprimé avec succès.');
            return $this->redirectToRoute('type_formation_affiche');
        }

        $this->addFlash('error', 'La suppression a échoué.');
        return $this->redirectToRoute('type_formation_affiche');
    }
}
