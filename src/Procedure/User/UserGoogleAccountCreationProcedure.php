<?php

namespace App\Procedure\User;

use App\Entity\User\User;
use App\Entity\User\UserGoogleAccount;
use Doctrine\ORM\EntityManagerInterface;

class UserGoogleAccountCreationProcedure
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    public function process(
        User $user,
        string $email,
        string $googleId,
        string $accessToken,
        \DateTime $tokenExpirationDatetime
    ): UserGoogleAccount {
        $userGoogleAccount = $this->userFacebookAccountDirector->create($user, $email, $googleId, $accessToken, $tokenExpirationDatetime);
        $user->addUserGoogleAccounts($userGoogleAccount);
        $this->entityManager->persist($userGoogleAccount);
        $this->entityManager->flush();

        return $userGoogleAccount;
    }
}
