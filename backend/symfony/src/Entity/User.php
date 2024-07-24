<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\SerializerInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
#[ORM\Index(name: 'created_by_idx',columns: ['created_by'])]
#[ORM\Index(name: 'updated_by_idx',columns: ['updated_by'])]
#[ORM\UniqueConstraint(name: 'email',columns: ['email'])]
#[ApiResource]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:list', 'user:item'])]
    private ?int $userid = null;

    #[ORM\Column(length: 150, unique: true)]
    #[Groups(['user:list', 'user:item'])]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    #[Groups(['user:list', 'user:item'])]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    #[Groups(['user:list', 'user:item'])]
    private ?string $phone = null;

    #[ORM\Column]
    #[Groups(['user:list', 'user:item'])]
    private ?int $createdBy = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['user:list', 'user:item'])]
    private ?\DateTimeInterface $createdDate = null;

    #[ORM\Column]
    #[Groups(['user:list', 'user:item'])]
    private ?int $updatedBy = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['user:list', 'user:item'])]
    private ?\DateTimeInterface $updatedDate = null;

    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: "users")]
    #[ORM\JoinTable(name: "user_group")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "userid")]
    #[ORM\InverseJoinColumn(name: "group_id", referencedColumnName: "groupid")]
    #[Groups(['user:list', 'user:item'])]
    private Collection $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    public function getUserId(): ?int
    {
        return $this->userid;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?int $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedDate(): \DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(int $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getUpdatedDate(): ?\DateTimeInterface
    {
        return $this->updatedDate;
    }

    public function setUpdatedDate(\DateTimeInterface $updatedDate): self
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;

            // Here we check if the user is already in the group and otherwise add it
            if (!$group->getUsers()->contains($this)) {
                $group->addUser($this);
            }
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {

            // Here we check if the user is in the group and otherwise remove it
            if ($group->getUsers()->contains($this)) {
                $group->removeUser($this);
            }
        }

        return $this;
    }

    /**
     * @throws \Exception
     *
     * @Route("/api/users/{id}", methods={"PATCH"}, defaults={"_api_item_operation_name"="patch"})
     */
    public function patch(
        int $id,
        Request $request,
        UserRepository $userRepository,
        GroupRepository $groupRepository,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['groups']) || !is_array($data['groups'])) {
            throw new \Exception('Please check your request, missing or incorrect fields found');
        }

        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('No user found for id ' . $id);
        }

        $user->getGroups()->clear();

        foreach ($data['groups'] as $groupUrl) {
            $groupId = (int)trim(parse_url($groupUrl, PHP_URL_PATH), '/');
            $group = $groupRepository->find($groupId);

            if (!$group) {
                throw $this->createNotFoundException('No group found for id ' . $groupId);
            }

            $user->addGroup($group);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse($serializer->serialize($user, 'json'), Response::HTTP_OK, [], true);
    }
}