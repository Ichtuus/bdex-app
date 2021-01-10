<?php

namespace App\Repository\User;

use App\Entity\User\UserGoogleAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserGoogleAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserGoogleAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserGoogleAccount[]    findAll()
 * @method UserGoogleAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGoogleAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGoogleAccount::class);
    }
}
