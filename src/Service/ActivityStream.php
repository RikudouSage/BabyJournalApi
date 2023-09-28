<?php

namespace App\Service;

use App\Entity\Child;
use App\EntityType\Activity;
use App\EntityType\ActivityRepository;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Uid\Uuid;

final readonly class ActivityStream
{
    /**
     * @param iterable<ActivityRepository> $activityRepositories
     */
    public function __construct(
        #[TaggedIterator('app.activity.repository')]
        private iterable $activityRepositories,
    ) {
    }

    /**
     * @param array<string, Uuid>|null $lastViewed
     * @return iterable<Activity>
     */
    public function getActivities(Child $child, ?array &$lastViewed = null): iterable
    {
        $lastViewed = [];

        /** @var ActivityRepository[] $repositories */
        $repositories = array_values([...$this->activityRepositories]);

        /** @var array<array<Activity>> $dbResults */
        $dbResults = [];
        $repositoryCount = 0;

        foreach ($repositories as $repository) {
            $dbResults[] = $repository->findBy([
                'child' => $child,
            ], ['id' => 'DESC']);
            ++$repositoryCount;
        }

        $max = max(array_map(fn ($array) => count($array), $dbResults));

        for ($i = 0; $i < $max; ++$i) {
            for ($j = 0; $j < $repositoryCount; ++$j) {
                $item = $dbResults[$j][$i] ?? null;
                if ($item === null) {
                    continue;
                }
                if ($i === 0) {
                    $lastViewed[$j] = $item->getId();
                }
                yield $item;
            }
        }

        foreach ($lastViewed as $index => $id) {
            unset($lastViewed[$index]);
            $lastViewed[$repositories[$index]->getClassName()] = $id;
        }
    }
}
