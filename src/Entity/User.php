<?php

namespace App\Entity;

use App\Repository\UserRepository;
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
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ApiToken", mappedBy="user")
     */
    private $tokens;

    /**
     * @ORM\OneToMany(targetEntity="Rating", mappedBy="users", fetch="EXTRA_LAZY")
     */
    private $ratings;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getToken(): ?Collection
    {
        return $this->tokens;
    }

    public function getTokens(): Collection
    {
        return $this->tokens;
    }

    public function setTokens($tokens): void
    {
        $this->tokens = $tokens;
    }

    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function setRatings($ratings): void
    {
        $this->ratings = $ratings;
    }

    public function getRoles(): ?string
    {
        return null;
    }

    public function getSalt(): string
    {
        return 'wetek-test';
    }

    public function getUsername(): ?string
    {
        return null;
    }

    public function eraseCredentials(): ?string
    {
        return null;
    }
}
