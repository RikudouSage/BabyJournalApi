<?php

namespace App\Service;

use App\Entity\ParentalUnitSetting as ParentalUnitSettingEntity;
use App\Entity\User;
use App\Enum\ParentalUnitSetting as ParentalUnitSettingEnum;
use App\Repository\ParentalUnitSettingRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class SettingsManager
{
    public function __construct(
        private ParentalUnitSettingRepository $repository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return array<string, string|null>
     */
    public function getSettings(User $user): array
    {
        $map = $this->getMap($user);

        $result = [];
        foreach (ParentalUnitSettingEnum::cases() as $setting) {
            $result[$setting->value] = ($map[$setting->value] ?? null)?->getValue() ?? null;
        }

        return $result;
    }

    public function setSetting(User $user, ParentalUnitSettingEnum $setting, string $value): void
    {
        $entity = $this->repository->createQueryBuilder('pus')
            ->andWhere('pus.setting = :setting')
            ->andWhere('pus.parentalUnit = :parentalUnit')
            ->setParameter('setting', $setting)
            ->setParameter('parentalUnit', $user->getParentalUnit()?->getId()?->toBinary())
            ->getQuery()
            ->getOneOrNullResult();
        if ($entity === null) {
            $entity = (new ParentalUnitSettingEntity())
                ->setSetting($setting)
                ->setParentalUnit($user->getParentalUnit())
            ;
        }
        assert($entity instanceof ParentalUnitSettingEntity);

        $entity->setValue($value);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @return array<string, ParentalUnitSettingEntity>
     */
    private function getMap(User $user): array
    {
        $result = [];
        $settingEntities = $user->getParentalUnit()?->getSettings() ?? [];
        foreach ($settingEntities as $settingEntity) {
            assert($settingEntity->getSetting() !== null);
            $result[$settingEntity->getSetting()->value] = $settingEntity;
        }

        return $result;
    }
}
