<?php

namespace App\Command;

use App\Entity\OAuthClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:oauth:client:add-redirect-uri')]
final class AddRedirectUriToOAuthClient extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                name: 'clientId',
                mode: InputArgument::REQUIRED,
                description: 'The client ID of the client',
            )
            ->addArgument(
                'redirectUri',
                mode: InputArgument::REQUIRED,
                description: 'The redirect URI to add',
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $entity = $this->entityManager->getRepository(OAuthClient::class)->findOneBy([
            'identifier' => $input->getArgument('clientId'),
        ]);

        if ($entity === null) {
            $io->error('Client not found');

            return self::FAILURE;
        }

        $redirectUris = $entity->getAllowedRedirectUris();
        $redirectUris[] = trim($input->getArgument('redirectUri'));
        $redirectUris = array_unique($redirectUris);
        $entity->setAllowedRedirectUris($redirectUris);

        $this->entityManager->flush();

        $io->success('Successfully added the redirect URI.');

        return self::SUCCESS;
    }
}
