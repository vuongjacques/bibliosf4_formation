<?php

namespace App\Repository;

use App\Entity\Livre;
use App\Entity\Emprunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    /**
     * @return Livre[] Returns an array of Livre objects
     */
    public function rechercheParTitre($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.titre LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->orderBy('l.auteur', 'ASC')
            ->addOrderBy('l.titre')
            ->getQuery()
            ->getResult()
        ;
    } 
    
    /**
     * Requête : SELECT l.* 
     *           FROM livre l JOIN emprunt e ON l.id = e.livre_id
     *          WHERE e. date_retour IS NULL
     * Requête avec jointure. La méthode livreNonRendus va renvoyer les livres dont la date de retour dans la table emprunt est égale à NULL
     */
    public function livresNonRendus()
    {
        return $this->createQueryBuilder('l')
            ->join(Emprunt::class, "e", "WITH", "l.id = e.livre")
            ->where("e.date_retour IS NULL")
            ->orderBy("l.auteur")
            ->addOrderBy("l.titre")
            ->getQuery()
            ->getResult()
        ;
    }

    public function livresDisponibles()
    {
        $em = $this->getEntityManager();
        return $em->createQuery("SELECT l FROM App\Entity\Livre l 
                                 WHERE l.id NOT IN (SELECT liv.id 
                                                  FROM App\Entity\Livre liv JOIN App\Entity\Emprunt e WITH liv.id = e.livre 
                                                  WHERE e.date_retour IS NULL)")
                    ->getResult();
    }

    // /**
    //  * @return Livre[] Returns an array of Livre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
