<?php

namespace App\Manager\User;

use App\Entity\User\User;
use App\Repository\User\UserGoogleAccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\GoogleUser;

class UserGoogleCreationManager
{
    private UserGoogleAccountRepository $userGoogleAccountRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        UserGoogleAccountRepository $userGoogleAccountRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->userGoogleAccountRepository = $userGoogleAccountRepository;
        $this->entityManager = $entityManager;
    }

    public function update(User $user, GoogleUser $googleUser, $credentials)
    {
        $userGoogleAccount = $this->userGoogleAccountRepository->findOneBy(['googleId' => $googleUser->getId(), 'user' => $user]);

        $expirationTokenDatetime = \DateTime::createFromFormat('U', $credentials->getExpires());

        $userGoogleAccount->setToken($credentials->getToken());
        $userGoogleAccount->setTokenExpirationDatetime($expirationTokenDatetime);
        $userGoogleAccount->setIsTokenValid(true);

        $this->entityManager->flush();
    }
}
