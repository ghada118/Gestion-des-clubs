<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\NotificationRepository;

class EtudiantController extends AbstractController
{
    #[Route('/etudiant', name: 'app_etudiant')]
    public function index(): Response
    {
        return $this->render('etudiant/index.html.twig', [
            'controller_name' => 'EtudiantController',
        ]);
    }


    #[Route('/mes-notifications', name: 'mes_notifications')]
    public function mesNotifications(NotificationRepository $notificationRepo)
    {
        $etudiant = $this->getUser(); // Récupère l'étudiant connecté
        $notifications = $notificationRepo->findBy(['etudiant' => $etudiant], ['dateEnvoi' => 'DESC']);

        return $this->render('etudiant/notifications.html.twig', [
            'notifications' => $notifications,
        ]);
    }
}
