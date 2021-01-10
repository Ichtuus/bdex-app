<?php

namespace App\Repository\User;

use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string $googleId
     *
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function findByGoogleId(string $googleId)
    {
        return $this->createQueryBuilder('user')
            ->join('user.userGoogleAccounts', 'user_google_accounts')
            ->where('user_google_accounts.googleId = :googleId')
            ->setParameter('googleId', $googleId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
