<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`groups`')]
#[ORM\UniqueConstraint(name: 'groupname', columns: ['groupname'])]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'group:item']),
        new GetCollection(normalizationContext: ['groups' => 'group:list'])
    ]
)]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['group:list', 'group:item'])]
    private ?int $groupid = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Groups(['group:list', 'group:item'])]
    private ?string $groupname = null;

    #[ORM\Column]
    #[Groups(['group:list', 'group:item'])]
    private ?int $createdBy = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['group:list', 'group:item'])]
    private ?\DateTimeInterface $createdDate = null;

    #[ORM\Column]
    #[Groups(['group:list', 'group:item'])]
    private ?int $updatedBy = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['group:list', 'group:item'])]
    private ?\DateTimeInterface $updatedDate = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'groups')]
    #[Groups(['group:list', 'group:item'])]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getGroupId(): ?int
    {
        return $this->groupid;
    }

    public function getGroupname(): ?string
    {
        return $this->groupname;
    }

    public function setGroupname(string $groupname): static
    {
        $this->groupname = $groupname;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(int $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): static
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(int $updatedBy): static
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getUpdatedDate(): ?\DateTimeInterface
    {
        return $this->updatedDate;
    }

    public function setUpdatedDate(\DateTimeInterface $updatedDate): static
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;

            // Here we check if the group is already in the user and otherwise add it
            if (!$user->getGroups()->contains($this)) {
                $user->addGroup($this);
            }
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {

            // Here we check if the group is in the user and otherwise remove it
            if ($user->getGroups()->contains($this)) {
                $user->removeGroup($this);
            }
        }

        return $this;
    }
}