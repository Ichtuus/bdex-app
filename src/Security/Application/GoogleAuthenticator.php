<?php

namespace App\Security\Application;

use App\Manager\User\UserGoogleCreationManager;
use App\Procedure\User\UserCreationProcedure;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GoogleAuthenticator extends SocialAuthenticator
{

    private UserRepository $userRepository;
    private ClientRegistry $clientRegistry;
    private EntityManagerInterface $em;
    private RouterInterface $router;
    private UserCreationProcedure $userCreationProcedure;
    private UserGoogleCreationManager $userCreationManager;

    public function __construct(
        UserRepository $userRepository,
        ClientRegistry $clientRegistry,
        EntityManagerInterface $em,
        RouterInterface $router,
        UserCreationProcedure $userCreationProcedure,
        UserGoogleCreationManager $userCreationManager
    ) {
        $this->userRepository = $userRepository;
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
        $this->userCreationProcedure = $userCreationProcedure;
        $this->userCreationManager = $userCreationManager;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'connect_google_check' && $request->isMethod('GET');
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getGoogleClient());
    }

    /**
     * @return GoogleClient
     */
    private function getGoogleClient()
    {
        return $this->clientRegistry->getClient('google');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var GoogleUser $googleUser */
        $googleUser = $this->getGoogleClient()->fetchUserFromToken($credentials);
        $email = $googleUser->getEmail();

        $existingUser = $this->userRepository->findOneBy(['googleId' => $googleUser->getId()]);

//        $email =
        if ($existingUser && !count($existingUser->getUserGoogleAccounts())) {
            die('die');
//            return $this->userSynchroniseFacebookAccount->process(
//                $existingUser,
//                $email,
//                $facebookUser->getId(),
//                $credentials->getToken(),
//                $credentials->getExpires()
//            );
        } elseif ($existingUser) {
            $this->userCreationManager->update($existingUser, $googleUser, $credentials);
            return $existingUser;
        }
        /** @var AccessTokenInterface $credentials */
        return $this->userCreationProcedure->process(
            $email,
            $googleUser->getId(),
            $credentials->getToken(),
            $credentials->getExpires()
        );
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // TODO: Implement start() method.
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return new RedirectResponse($this->router->generate('app_home', ['_fragment' => '/dashboard']));
    }
}
