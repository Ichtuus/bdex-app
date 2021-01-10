<?php

namespace App\Entity\User;

use App\Repository\User\UserGoogleAccountRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass=UserGoogleAccountRepository::class)
 */
class UserGoogleAccount
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $googleId;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;
    /**
     * @ORM\Column(type="text")
     */
    private $token;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastTokenUsageDatetime;
    /**
     * @var boolean
     * @ORM\Column(type="smallint", options={"default" : 1})
     */
    private $isTokenValid;
    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDatetime;
    /**
     * @ORM\Column(type="datetime")
     */
    private $tokenExpirationDatetime;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userGoogleAccounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->creationDatetime = new \DateTime();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getCreationDatetime(): ?\DateTimeInterface
    {
        return $this->creationDatetime;
    }

    public function setCreationDatetime(\DateTimeInterface $creationDatetime): self
    {
        $this->creationDatetime = $creationDatetime;

        return $this;
    }

    public function getTokenExpirationDatetime(): ?\DateTimeInterface
    {
        return $this->tokenExpirationDatetime;
    }

    public function setTokenExpirationDatetime(\DateTimeInterface $tokenExpirationDatetime): self
    {
        $this->tokenExpirationDatetime = $tokenExpirationDatetime;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLastTokenUsageDatetime(): ?\DateTimeInterface
    {
        return $this->lastTokenUsageDatetime;
    }

    public function setLastTokenUsageDatetime(?\DateTimeInterface $lastTokenUsageDatetime): self
    {
        $this->lastTokenUsageDatetime = $lastTokenUsageDatetime;

        return $this;
    }

    public function isTokenValid(): bool
    {
        return $this->isTokenValid;
    }

    public function setIsTokenValid(bool $isTokenValid): self
    {
        $this->isTokenValid = $isTokenValid;

        return $this;
    }
}
