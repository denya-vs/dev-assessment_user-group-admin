<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController
{
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
            throw new NotFoundHttpException('No user found for id ' . $id);
        }

        $user->getGroups()->clear();

        foreach ($data['groups'] as $groupUrl) {
            $groupId = (int)trim(parse_url($groupUrl, PHP_URL_PATH), '/');
            $group = $groupRepository->find($groupId);

            if (!$group) {
                throw new NotFoundHttpException('No group found for id ' . $groupId);
            }

            $user->addGroup($group);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse($serializer->serialize($user, 'json'), Response::HTTP_OK, [], true);
    }
}