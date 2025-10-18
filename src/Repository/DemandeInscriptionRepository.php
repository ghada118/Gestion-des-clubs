<?php

namespace App\Repository;

use App\Entity\DemandeInscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandeInscription>
 */
class DemandeInscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeInscription::class);
    }

//    /**
//     * @return DemandeInscription[] Returns an array of DemandeInscription objects
//     */
//    public function findPendingWithAvailableSpots(): array
//{
   // return $this->createQueryBuilder('d')
   //    ->join('d.club', 'c')
  //     ->where('d.statut = :statut')
 //      ->andWhere('c.placesDisponibles > 0')
 //      ->setParameter('statut', 'en attente')
 //      ->getQuery()
//       ->getResult();
//}

}
