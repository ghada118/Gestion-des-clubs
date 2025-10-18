<?php



namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\EvenementRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Evenement;
use App\Form\EvenementType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;




class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'app_evenement')]
    public function index(): Response
    {
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }


    #[Route('/AjoutA', name: 'app_AjoutA')]
    public function AjoutA(ManagerRegistry $doctrine, Request $request): Response
    {
        // Instancier un objet
        $Event = new Evenement();
    
        // Création du formulaire
        $form = $this->createForm(EvenementType::class, $Event);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData(); // 'image' doit correspondre au nom du champ ajouté dans le formulaire
    
            if ($imageFile) {
                // Générer un nom unique pour l'image
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
    
                try {
                    // Déplacer le fichier vers le répertoire de stockage
                    $imageFile->move(
                        $this->getParameter('images_directory'), // Défini dans services.yaml
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Erreur lors du téléchargement de l\'image.');
                }
    
                // Stocker le nom de fichier dans l'entité
                $Event->setImage($newFilename);
            }
    
            // Enregistrer l'événement dans la base de données
            $em = $doctrine->getManager();
            $em->persist($Event);
            $em->flush();
    
            // Rediriger après l'ajout
            return $this->redirectToRoute('app_AfficheA');
        }
    
        // Afficher le formulaire
        return $this->renderForm('evenement/AjoutA.html.twig', [
            'f' => $form,
        ]);
    }
    


    #[Route('/AfficheA', name: 'app_AfficheA')]
    public function AfficheA(EvenementRepository $rep): Response
    {
        $evenements =$rep->findAll();
        return $this->render('evenement/AfficheA.html.twig', [
            'evenement' => $evenements,
            
            
        ]);
    }



    #[Route('/ModifierA/{id}', name: 'app_ModifierA')]
    public function ModifierA(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $em = $doctrine->getManager();
        $event = $em->getRepository(Evenement::class)->find($id);
    
        if (!$event) {
            throw $this->createNotFoundException('L\'événement avec l\'ID ' . $id . ' n\'existe pas.');
        }
    
        $currentImage = $event->getImage(); // Image actuelle
        $form = $this->createForm(EvenementType::class, $event);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
    
            if ($imageFile) {
                $uploadDir = $this->getParameter('images_directory'); // Défini dans services.yaml
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
    
                try {
                    $imageFile->move($uploadDir, $newFilename);
                    $event->setImage($newFilename); // Mettre à jour l'image
                } catch (FileException $e) {
                    throw new \Exception('Erreur lors du téléchargement de l\'image.');
                }
            } else {
                $event->setImage($currentImage); // Garder l'image actuelle si aucune nouvelle n'est envoyée
            }
    
            $em->flush();
            return $this->redirectToRoute('app_AfficheA');
        }
    
        return $this->renderForm('evenement/ModifierA.html.twig', [
            'f' => $form,
            'currentImage' => $currentImage,
        ]);
    }
    
    #[Route('/SupprimeA/{id}', name: 'app_SupprimeA')]
    public function SupprimeA(ManagerRegistry $doctrine, int $id): Response
    {
        // Récupérer l'événement par son ID
        $em = $doctrine->getManager();
        $event = $em->getRepository(Evenement::class)->find($id);
    
        // Vérifier si l'événement existe
        if (!$event) {
            throw $this->createNotFoundException('L\'événement avec l\'ID ' . $id . ' n\'existe pas.');
        }
    
        // Supprimer l'événement
        $em->remove($event);
        $em->flush();
    
        // Rediriger vers la liste des événements
        return $this->redirectToRoute('app_AfficheA');
    }





    #[Route('/events', name: 'app_front_events')]
    public function showEvents(EvenementRepository $eventRepository): Response
    {
        // Récupère tous les événements depuis la base de données
        $events = $eventRepository->findAll();
    
        // Retourne la vue 'front/events.html.twig' avec les événements
        return $this->render('front/events.html.twig', [
            'events' => $events  , // Passe les événements à la vue
        ]);
    }
    











    



    #[Route('/recherche', name: 'app_recherche')]
    public function rechercher(Request $request, EvenementRepository $evenementRepository): Response
    {
        $keyword = $request->query->get('q', ''); // Récupère le mot-clé depuis la requête
        $evenements = $keyword ? $evenementRepository->searchByKeyword($keyword) : [];
    
        return $this->render('evenement/recherche.html.twig', [
            'evenements' => $evenements,
            'keyword' => $keyword,
        ]);
    }
    


















}

