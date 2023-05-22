<?php

namespace App\Controller;

use App\Entity\ParentalUnit;
use App\Entity\User;
use App\Enum\ParentalUnitSetting;
use App\Repository\ParentalUnitRepository;
use App\Request\CreateAccountRequest;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/account')]
final class AccountController extends AbstractController
{
    #[Route('/create', name: 'app.account.create', methods: [Request::METHOD_POST])]
    public function createAccount(
        CreateAccountRequest $request,
        EntityManagerInterface $entityManager,
        ParentalUnitRepository $parentalUnitRepository,
    ): JsonResponse {
        $parentalUnit = $request->parentalUnitId
            ? $parentalUnitRepository->findOneBy([
                'shareCode' => $request->parentalUnitId,
            ])
            : (new ParentalUnit())->setName($request->parentalUnitName);
        if ($parentalUnit === null) {
            throw $this->createNotFoundException('Family not found');
        }
        $user = (new User())
            ->setName($request->name)
            ->setParentalUnit($parentalUnit)
        ;

        $entityManager->persist($parentalUnit);
        $entityManager->persist($user);

        $entityManager->flush();

        return new JsonResponse([
            'id' => (string) $user->getId(),
        ], status: Response::HTTP_CREATED);
    }

    #[Route('/refresh-share-code', name: 'app.account.refresh_share_code', methods: [Request::METHOD_POST])]
    public function refreshShareCode(
        Security $security,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $currentUser = $security->getUser();
        assert($currentUser instanceof User);

        $parentalUnit = $currentUser->getParentalUnit();
        assert($parentalUnit instanceof ParentalUnit);

        $parentalUnit->setShareCode(Uuid::v4());
        $entityManager->persist($parentalUnit);
        $entityManager->flush();

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }

    #[Route('/settings', name: 'app.account.settings.get', methods: [Request::METHOD_GET])]
    public function settings(SettingsManager $settings): JsonResponse
    {
        $user = $this->getUser();
        assert($user instanceof User);

        return new JsonResponse($settings->getSettings($user));
    }

    #[Route('/settings', name: 'app.account.settings.update', methods: [Request::METHOD_PATCH])]
    public function saveSettings(SettingsManager $settings, Request $request): JsonResponse
    {
        $user = $this->getUser();
        assert($user instanceof User);

        $content = json_decode($request->getContent(), true, flags: JSON_THROW_ON_ERROR);
        assert(is_array($content));

        foreach ($content as $settingName => $value) {
            $settings->setSetting($user, ParentalUnitSetting::from($settingName), $value);
        }

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
