<?php

namespace App\Controller;

use App\Enum\Scope;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ChatGptPluginController extends AbstractController
{
    #[Route('/.well-known/ai-plugin.json', name: 'app.chatgpt_plugin.manifest')]
    public function pluginManifest(
        UrlGeneratorInterface $urlGenerator,
        #[Autowire('%app.info.display_name%')] string $appName,
        #[Autowire('%app.info.machine_name%')] string $machineName,
        #[Autowire('%chatgpt.human_description%')] string $humanDescription,
        #[Autowire('%chatgpt.machine_description%')] string $machineDescription,
        #[Autowire('%app.info.frontend_url%')] string $frontendUrl,
        #[Autowire('%app.info.support_email%')] string $supportEmail,
        #[Autowire('%app.info.legal_info_url%')] string $legalInfoUrl,
        #[Autowire('%chatgpt.openai_verification_token%')] string $verificationToken,
    ): JsonResponse {
        $baseUrl = trim($urlGenerator->generate('rikudou_json_api.home', referenceType: UrlGeneratorInterface::ABSOLUTE_URL), '/');

        return new JsonResponse([
            'schema_version' => 'v1',
            'name_for_human' => $appName,
            'name_for_model' => $machineName,
            'description_for_human' => $humanDescription,
            'description_for_model' => $machineDescription,
            'api' => [
                'type' => 'openapi',
                'url' => "{$baseUrl}/chatgpt.openapi.yaml",
                'is_user_authenticated' => false,
            ],
            'logo_url' => "{$frontendUrl}/assets/icons/icon-512x512.png",
            'contact_email' => $supportEmail,
            'legal_info_url' => $legalInfoUrl,
            'auth' => [
                'type' => 'oauth',
                'client_url' => $urlGenerator->generate('app.oauth.authorize', referenceType: UrlGeneratorInterface::ABSOLUTE_URL),
                'scope' => implode(' ', array_map(static fn (Scope $scope) => $scope->value, Scope::cases())),
                'authorization_url' => $urlGenerator->generate('app.oauth.token', referenceType: UrlGeneratorInterface::ABSOLUTE_URL),
                'authorization_content_type' => 'application/x-www-form-urlencoded',
                'verification_tokens' => [
                    'openai' => $verificationToken,
                ],
            ],
        ]);
    }
}
