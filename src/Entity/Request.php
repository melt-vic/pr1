<?php

namespace App\Entity;

use App\Repository\RequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
class Request
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreated = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'Request', targetEntity: RequestLine::class, orphanRemoval: true)]
    private Collection $requestLines;

    public function __construct()
    {
        $this->requestLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): static
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, RequestLine>
     */
    public function getRequestLines(): Collection
    {
        return $this->requestLines;
    }

    public function addRequestLine(RequestLine $requestLine): static
    {
        if (!$this->requestLines->contains($requestLine)) {
            $this->requestLines->add($requestLine);
            $requestLine->setRequest($this);
        }

        return $this;
    }

    public function removeRequestLine(RequestLine $requestLine): static
    {
        if ($this->requestLines->removeElement($requestLine)) {
            // set the owning side to null (unless already changed)
            if ($requestLine->getRequest() === $this) {
                $requestLine->setRequest(null);
            }
        }

        return $this;
    }
}
