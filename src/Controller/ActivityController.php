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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('', name: 'app.activities.list')]
    public function listActivities(): JsonResponse {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }
        $child = $user->getSelectedChild();
        if (!$child instanceof Child) {
            return new JsonResponse([]);
        }

        $results = [];
        foreach ($this->activityRepositories as $repository) {
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
