<?php

namespace App\Controller;

use App\Entity\Child;
use App\Entity\FeedingActivity;
use App\Entity\User;
use App\EntityType\Activity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activities')]
final class ActivityController extends AbstractController
{
    #[Route('', name: 'app.activities.list')]
    public function listActivities(
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }
        $child = $user->getSelectedChild();
        if (!$child instanceof Child) {
            return new JsonResponse([]);
        }

        /** @var EntityRepository[] $repositories */
        $repositories = [
            $entityManager->getRepository(FeedingActivity::class),
        ];

        $results = [];
        foreach ($repositories as $repository) {
            $results = array_merge($results, $repository->findBy([
                'child' => $child,
            ]));
        }

        return new JsonResponse(array_map(function (object $activity) {
            if (!$activity instanceof Activity) {
                throw new LogicException(
                    sprintf('All objects must be an instance of %s, %s is not', Activity::class, get_class($activity)),
                );
            }

            return $activity->toJson();
        }, $results));
    }
}
