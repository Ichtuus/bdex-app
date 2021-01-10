<?php

namespace App\Entity\User;

use DateTime;
use App\Repository\User\UserRepository;
use App\Entity\User\UserGoogleAccount;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private ?string $id;
    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private ?string $email;
    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];
    /**
     * @ORM\Column(type="string")
     */
    private string $password;
    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDatetime;
    /**
     * @ORM\OneToMany(targetEntity=UserGoogleAccount::class, mappedBy="user")
     */
    private $userGoogleAccounts;
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isVerified = false;
    /**
     * @ORM\Column(type="string", length=5, nullable=false, options={"default":"en"})
     */
    private string $locale;
    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    private $googleId;

    public function __construct()
    {
        $this->creationDatetime = new DateTime();
        $this->userGoogleAccounts = new ArrayCollection();
        $this->locale = 'en';
    }

    public function getId(): ?string
    {
        return $this->id;
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

    /**
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCreationDatetime(): DateTime
    {
        return $this->creationDatetime;
    }

    public function setCreationDatetime(DateTimeInterface $creationDatetime): self
    {
        $this->creationDatetime = $creationDatetime;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     *
     * @return $this
     */
    public function setLocale(string $locale): User
    {
        $this->locale = $locale;

        return $this;
    }

    public function serialize()
    {
        return serialize([
            $this->email,
            $this->password,
            $this->isVerified,
            $this->id,
            $this->locale,
        ]);
    }

    public function unserialize($serialized)
    {
        [
            $this->email,
            $this->password,
            $this->isVerified,
            $this->id,
            $this->locale,
        ] = unserialize($serialized);
    }


    public function getGoogleId()
    {
        return $this->googleId;
    }

    public function setGoogleId($googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * @return Collection|UserGoogleAccount[]
     */
    public function getUserGoogleAccounts(): Collection
    {
        return $this->userGoogleAccounts;
    }

    public function addUserGoogleAccounts(UserGoogleAccount $userGoogleAccounts): self
    {
        if (!$this->userGoogleAccounts->contains($userGoogleAccounts)) {
            $this->userGoogleAccounts[] = $userGoogleAccounts;
            $userGoogleAccounts->setUser($this);
        }

        return $this;
    }

    public function removeUserGoogleAccounts(UserGoogleAccount $userGoogleAccounts): self
    {
        if ($this->userGoogleAccounts->contains($userGoogleAccounts)) {
            $this->userGoogleAccounts->removeElement($userGoogleAccounts);

            if ($userGoogleAccounts->getUser() === $this) {
                $userGoogleAccounts->setUser(null);
            }
        }

        return $this;
    }
}
