<?php

namespace App\Procedure\User;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;

class UserCreationProcedure
{
    private EntityManagerInterface $em;
    private UserGoogleAccountCreationProcedure $userGoogleAccountCreationProcedure;

    public function __construct (
        EntityManagerInterface $em,
        UserGoogleAccountCreationProcedure $userGoogleAccountCreationProcedure
    ) {
        $this->em = $em;
        $this->userGoogleAccountCreationProcedure = $userGoogleAccountCreationProcedure;
    }

    public function process(string $email, string $googleId, string $accessToken, int $tokenExpirationTimestamp): User
    {
        $user = (new User())->setEmail($email)->setPassword('');
        $user->setGoogleId($googleId)
            ->setIsVerified(true)
        ;
        $this->em->persist($user);
        $this->em->flush();

        $expirationTokenDatetime = \DateTime::createFromFormat('U', $tokenExpirationTimestamp);

        $this->userGoogleAccountCreationProcedure->process($user, $email, $googleId, $accessToken, $expirationTokenDatetime);
        return $user;
    }

}
