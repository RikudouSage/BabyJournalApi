<?php

namespace App\Controller;

use App\Entity\ParentalUnit;
use App\Entity\User;
use App\Repository\ParentalUnitRepository;
use App\Repository\UserRepository;
use App\Request\CreateAccountRequest;
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
}
