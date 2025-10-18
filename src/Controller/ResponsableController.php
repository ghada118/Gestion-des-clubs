<?php

namespace App\Controller;

use App\Entity\DemandeInscription;
use App\Entity\Notification;
use App\Entity\Etudiant;
use App\Repository\DemandeInscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ResponsableController extends AbstractController
{
    /**
     * Envoie une notification par email à un étudiant
     */
    private function sendNotificationEmail(Etudiant $etudiant, string $message, MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('noreply@votre-site.com')
            ->to($etudiant->getEmail())
            ->subject('Notification de votre demande d\'inscription')
            ->text($message);

        $mailer->send($email);
    }

    /**
     * Gérer une demande d'inscription
     */
    #[Route('/gerer-demande/{id}/{action}', name: 'gerer_demande')]
    public function gererDemande(
        $id,
        $action,
        DemandeInscriptionRepository $demandeInscriptionRepository,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response {
        // Récupérer la demande d'inscription
        $demande = $demandeInscriptionRepository->find($id);

        if (!$demande) {
            $this->addFlash('error', 'Demande non trouvée.');
            return $this->redirectToRoute('app_demandes');
        }

        $etudiant = $demande->getEtudiant();
        $club = $demande->getClub();
        $message = '';

        if ($action === 'accepter') {
            if ($club->getPlacesDisponibles() > 0) {
                $demande->setStatut('acceptée');
                $club->setPlacesDisponibles($club->getPlacesDisponibles() - 1);
                $message = 'Félicitations ! Votre demande d\'inscription au club "' . $club->getName() . '" a été acceptée.';
            } else {
                $this->addFlash('error', 'Le club n\'a plus de places disponibles.');
                return $this->redirectToRoute('app_demandes');
            }
        } elseif ($action === 'refuser') {
            $demande->setStatut('refusée');
            $message = 'Désolé, votre demande d\'inscription au club "' . $club->getName() . '" a été refusée.';
        } else {
            $this->addFlash('error', 'Action invalide.');
            return $this->redirectToRoute('app_demandes');
        }

        // Sauvegarder les modifications
        $em->flush();

        // Ajouter une notification
        if ($message !== '') {
            $notification = new Notification();
            $notification->setEtudiant($etudiant);
            $notification->setMessage($message);
            $notification->setCreatedAt(new \DateTime());

            $em->persist($notification);
            $em->flush();

            // Envoyer un email
            $this->sendNotificationEmail($etudiant, $message, $mailer);

            $this->addFlash('success', 'La demande a été ' . $demande->getStatut() . ' et l\'étudiant a été notifié.');
        }

        return $this->redirectToRoute('app_demandes');
    }

    /**
 * Afficher les demandes d'inscription
 */
    #[Route('/demandes', name: 'app_demandes')]
    public function afficherDemandes(
        DemandeInscriptionRepository $demandeInscriptionRepository,
        EntityManagerInterface $entityManager): Response {
        // Exemple d'ID d'étudiant, à modifier selon votre logique (par exemple via la session)
        $etudiantId ;  // Remplacez ceci par l'ID que vous souhaitez utiliser, ou récupérez-le autrement

        // Vérifier si l'étudiant avec cet ID existe
        $etudiant = $entityManager->getRepository(Etudiant::class)->find(3);

        if (!$etudiant) {
            $this->addFlash('error', 'Étudiant non trouvé.');
            return $this->redirectToRoute('home'); // Rediriger vers une page par défaut
        }

        // Récupérer les demandes d'inscription pour cet étudiant
        $demandes = $demandeInscriptionRepository->findBy(['etudiant' => $etudiant]);

        return $this->render('gere_demande/demandes.html.twig', [
            'demandes' => $demandes,
            'etudiant' => $etudiant,
        ]);
    }



    /**
     * Afficher les notifications d'un étudiant
     */
    #[Route('/notifications/{etudiantId}', name: 'app_notifications')]
    public function afficherNotifications($etudiantId, EntityManagerInterface $em): Response
    {
        $etudiant = $em->getRepository(Etudiant::class)->find($etudiantId);

        if (!$etudiant) {
            $this->addFlash('error', 'Étudiant non trouvé.');
            return $this->redirectToRoute('app_demandes');
        }

        $notifications = $em->getRepository(Notification::class)->findBy(['etudiant' => $etudiant]);

        return $this->render('gere_demande/notifications.html.twig', [
            'notifications' => $notifications,
            'etudiant' => $etudiant,
        ]);
    }
}
