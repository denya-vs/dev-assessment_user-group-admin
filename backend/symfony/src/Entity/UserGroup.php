<?php
//
//namespace App\Entity;
//
//use ApiPlatform\Metadata\ApiResource;
//use ApiPlatform\Metadata\Delete;
//use ApiPlatform\Metadata\Get;
//use ApiPlatform\Metadata\GetCollection;
//use ApiPlatform\Metadata\Post;
//use ApiPlatform\Metadata\Put;
//use App\Repository\UserGroupRepository;
//use Doctrine\ORM\Mapping as ORM;
//
//#[ORM\Entity(repositoryClass: UserGroupRepository::class)]
//#[ApiResource(
//    operations: [
//        new GetCollection(),
//        new Get(),
//        new Post(),
//        new Put(),
//        new Delete()
//    ]
//)]
//class UserGroup
//{
//    #[ORM\Id]
//    #[ORM\GeneratedValue]
//    #[ORM\Column]
//    private ?int $id = null;
//
//    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userGroups')]
//    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'userid', nullable: false)]
//    private ?User $user = null;
//
//    #[ORM\ManyToOne(targetEntity: Group::class)]
//    #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'groupid', nullable: false)]
//    private ?Group $userGroup = null;
//
//    public function getId(): ?int
//    {
//        return $this->id;
//    }
//
//    public function getUser(): ?User
//    {
//        return $this->user;
//    }
//
//    public function setUser(?User $user): static
//    {
//        $this->user = $user;
//
//        return $this;
//    }
//
//    public function getUserGroup(): ?Group
//    {
//        return $this->userGroup;
//    }
//
//    public function setUserGroup(?Group $userGroup): static
//    {
//        $this->userGroup = $userGroup;
//
//        return $this;
//    }
//}
