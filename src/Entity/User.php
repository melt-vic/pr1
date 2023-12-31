<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80, nullable: true)]
    #[Assert\Length(
        max: 80,
        maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres'
    )]
    private ?string $name = null;

    #[ORM\Column(length: 60, nullable: true)]
    #[Assert\Length(
        min: 8,
        minMessage: 'La contraseña debe tener al menos {{ limit }} caracteres'
    )]
    private ?string $password = null;

    #[Assert\NotBlank()]
    #[Assert\Email(
        message: 'El email "{{ value }}" no es una dirección válida'
    )]
    #[Assert\Length(
        min: 6,
        max: 50,
        minMessage: 'El email debe tener al menos {{ limit }} caracteres',
        maxMessage: 'El email no puede tener más de {{ limit }} caracteres'
    )]
    #[ORM\Column(length: 40, unique: true)]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[Assert\NotBlank()]
    #[Assert\Length(
        min: 10,
        max: 1000,
        minMessage: 'La dirección debe tener al menos {{ limit }} caracteres',
        maxMessage: 'La dirección no puede tener más de {{ limit }} caracteres'
    )]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $address;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserType $type = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Request::class)]
    private Collection $requests;

    public function __construct()
    {
        $this->requests = new ArrayCollection();
        $this->roles = ['ROLE_USER'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getType(): ?UserType
    {
        return $this->type;
    }

    public function setType(?UserType $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Request>
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): static
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
            $request->setUser($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getUser() === $this) {
                $request->setUser(null);
            }
        }

        return $this;
    }

    public function needsRehash(PasswordAuthenticatedUserInterface $user): bool
    {
        // TODO: Implement needsRehash() method.
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
