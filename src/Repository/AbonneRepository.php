<?php

namespace App\Repository;

use App\Entity\Abonne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Abonne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abonne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abonne[]    findAll()
 * @method Abonne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonneRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonne::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof Abonne) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }


 /**
     * @return Abonne[] Returns an array of Abonne objects
     */

    public function recherche($mot)
    {
        //SELECT a.* FROM `abonne` a WHERE a.titre like "%une%"
        return $this->createQueryBuilder('a')
            ->Where('a.pseudo LIKE :mot OR a.nom LIKE :mot  OR a.prenom LIKE :mot')
            //->orWhere('a.auteur LIKE :titre')
            //->andWhere()
            ->setParameter('mot', "%$mot%")
            ->orderBy('a.nom', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }



    // /**
    //  * @return Abonne[] Returns an array of Abonne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Abonne
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
