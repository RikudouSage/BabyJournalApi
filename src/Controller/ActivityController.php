<?php

namespace App\Controller;

use App\Entity\Child;
use App\Entity\FeedingActivity;
use App\Entity\User;
use App\EntityType\Activity;
use App\EntityType\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use LogicException;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/activities')]
final class ActivityController extends AbstractController
{
    /**
     * @param iterable<ActivityRepository> $activityRepositories
     */
    public function __construct(
        #[TaggedIterator('app.activity.repository')]
        private readonly iterable $activityRepositories,
    ) {
    }

    #[Route('/changes', name: 'app.activities.changes')]
    public function getActivityStreamChanges(EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }
        $child = $user->getSelectedChild();
        if (!$child instanceof Child) {
            return new JsonResponse([]);
        }

        $lastViewed = $user->getNewestActivitiesViewed();
        if ($lastViewed === null) {
            return $this->listActivities($entityManager);
        }

        $results = [];

        foreach ($this->activityRepositories as $repository) {
            $uuid = $lastViewed[$repository->getClassName()] ?? null;
            if ($uuid === null) {
                $activities = $repository->findBy([
                    'child' => $child,
                ]);
            } else {
                $activities = $entityManager
                    ->createQueryBuilder()
                    ->select('entity')
                    ->from($repository->getClassName(), 'entity')
                    ->andWhere('entity.id > :uuid')
                    ->setParameter('uuid', Uuid::fromString($uuid), UuidType::NAME)
                    ->getQuery()
                    ->getResult()
                ;
            }

            if (count($activities)) {
                $last = $activities[array_key_last($activities)];
                assert($last instanceof Activity);
                $lastViewed[$repository->getClassName()] = $last->getId();
            }

            $results = array_merge($results, $activities);
        }

        $user->setNewestActivitiesViewed($lastViewed);
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(array_map(function (object $activity) {
            if (!$activity instanceof Activity) {
                throw new LogicException(
                    sprintf('All objects must be an instance of %s, %s is not', Activity::class, get_class($activity)),
                );
            }

            return $activity->toJson();
        }, $results));
    }

    #[Route('', name: 'app.activities.list')]
    public function listActivities(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $perPage = 500;
        $page = $request->query->getInt('page', 1);

        $user = $this->getUser();
        if (!$user instanceof User) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }
        $child = $user->getSelectedChild();
        if (!$child instanceof Child) {
            return new JsonResponse([]);
        }

        $lastViewed = [];

        $results = [];
        foreach ($this->activityRepositories as $repository) {
            $activities = $repository->findBy([
                'child' => $child,
            ]);
            if (count($activities)) {
                $last = $activities[array_key_last($activities)];
                assert($last instanceof Activity);
                $lastViewed[$repository->getClassName()] = $last->getId();
            }
            $results = array_merge($results, $repository->findBy([
                'child' => $child,
            ]));
        }

        $user->setNewestActivitiesViewed($lastViewed);
        $entityManager->persist($user);
        $entityManager->flush();

        $results = array_slice($results, $page - 1, $perPage);

        return new JsonResponse(array_map(function (object $activity) {
            if (!$activity instanceof Activity) {
                throw new LogicException(
                    sprintf('All objects must be an instance of %s, %s is not', Activity::class, get_class($activity)),
                );
            }

            return $activity->toJson();
        }, $results), headers: [
            'X-Total-Count' => count($results),
            'X-Per-Page' => $perPage,
        ]);
    }
}
