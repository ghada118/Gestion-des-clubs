<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
// Ajoutez cette ligne au début du fichier
use App\Repository\FormationRepository;
use App\Repository\ClubRepository;

use App\Entity\Club;
use App\Form\ClubType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;




class ServiceController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
          
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('service/contact.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }


    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('service/about.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }

    #[Route('/testimonials ', name: 'app_testimonials')]
    public function  testimonials (): Response
    {
        return $this->render('service/testimonials.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }

    #[Route('/blog', name: 'app_blog')]
    public function services(): Response
    {
        return $this->render('service/blog.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }

    #[Route('/tutorials', name: 'app_tutorials')]
    public function tutorials(): Response
    {
        return $this->render('service/tutorials.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }
    #[Route('/front', name: 'app_front')]  // Assurez-vous que cette route correspond à votre page d'accueil
    public function front(FormationRepository $formationRepository): Response
    {
        // Récupérer toutes les formations de la base de données
        $formations = $formationRepository->findAll();  // Utilisez findBy() si vous avez des critères spécifiques
        
        // Renvoyer les formations à la vue
        return $this->render('service/front.html.twig', [
            'formations' => $formations,
        ]);
    }






    #[Route('/clubaceuil', name: 'app_clubaceuil', methods: ['GET'])]
    public function afficheClubAceuil(ClubRepository $repo): Response
    {
        // Récupérer tous les clubs depuis la base de données
        $clubs = $repo->findAll();
    
        // Rendre la vue avec la liste des clubs
        return $this->render('service/clubaceuil.html.twig', [
            'clubs' => $clubs,
        ]);
    }

    
    #[Route('/recherche-clubs', name: 'app_recherche_clubs')]
    public function rechercheClubs(Request $request, ClubRepository $clubRepository): Response
    {
        $searchTerm = $request->query->get('search', ''); // Recherche par mot-clé
        $type = $request->query->get('type', '');         // Recherche par type de club

        // Recherche des clubs en fonction des critères
        $clubs = $clubRepository->createQueryBuilder('c')
            ->leftJoin('c.type', 't') // Relation entre club et type
            ->where('c.name LIKE :search OR c.description LIKE :search')
            ->setParameter('search', '%' . $searchTerm . '%');

        if ($type) {
            $clubs->andWhere('t.categorieClub LIKE :type')
                ->setParameter('type', '%' . $type . '%');
        }

        $results = $clubs->getQuery()->getResult();

        return $this->render('service/clubaceuil.html.twig', [
            'clubs' => $results,
            'search' => $searchTerm,
            'type' => $type,
        ]);
    }

    

}








