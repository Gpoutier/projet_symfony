<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\modele\FiltreSortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function save(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function sortieFiltre(FiltreSortie $filtreSortie)
    {
        $queryBuilder = $this->createQueryBuilder('sortie');

        if ($filtreSortie ->getNom())
            $queryBuilder
                ->andWhere('sortie.nom like :nom')
                ->setParameter('nom', '%'.$filtreSortie ->getNom().'%');

        if ($filtreSortie ->getCampus()) {
            $queryBuilder -> andWhere('sortie.campus = :idCampus')
                ->setParameter('idCampus', $filtreSortie->getCampus());
        }
        if ($filtreSortie ->getDatedebut()){
            $queryBuilder ->andWhere('sortie.dateHeureDebut >= :datedebut')
                ->setParameter('datedebut', $filtreSortie ->getDatedebut());
        }
        if ($filtreSortie ->getDatefin()){
            $queryBuilder ->andWhere('sortie.dateHeureDebut <= :datefin')
                ->setParameter('datefin', $filtreSortie ->getDatefin());
        }
        if ($filtreSortie ->getOrganisateur()){
            $queryBuilder ->andWhere('sortie.organisateur = :organisateur')
                ->setParameter('organisateur',$filtreSortie ->getIduser());

        }
        if ($filtreSortie ->getInscrit()){
            $queryBuilder
                ->InnerJoin('sortie.participants', 'p')
                ->andWhere('p.id = :inscrit')
                ->setParameter('inscrit', $filtreSortie ->getIduser());
        }
        if ($filtreSortie->getPasInscrit()) {
            $queryBuilder
                -> andWhere(':user NOT MEMBER OF sortie.participants')
                -> setParameter('user', $filtreSortie ->getIduser());
        }
        if ($filtreSortie -> getSortieFermees()){
            $queryBuilder ->andWhere('sortie.etat = 47');
        }

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
