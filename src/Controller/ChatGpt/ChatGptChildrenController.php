<?php

namespace App\Controller\ChatGpt;

use App\Entity\Child;
use App\Entity\User;
use App\Enum\Scope;
use App\Service\Decryptor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/chatgpt/children')]
final class ChatGptChildrenController extends AbstractController
{
    #[Route('/names', name: 'chatgpt.children.names')]
    public function getChildrenNames(Decryptor $decryptor): JsonResponse
    {
        $this->denyAccessUnlessGranted('OAUTH_SCOPE', Scope::ChildInfo);
        $user = $this->getUser();
        assert($user instanceof User);

        if (!$decryptor->hasKeysStored($user)) {
            return new JsonResponse([
                'error' => "The user didn't store their private keys, it's impossible to decrypt stored data.",
            ], Response::HTTP_FORBIDDEN);
        }

        $key = $decryptor->getPrivateKey($user);

        $user = $this->getUser();
        assert($user instanceof User);

        return new JsonResponse(array_map(
            fn (Child $child) => $child->getName() !== null ? $decryptor->decrypt($key, $child->getName()) : $child->getDisplayName(),
            [...$user->getParentalUnit()->getChildren()],
        ));
    }
}
