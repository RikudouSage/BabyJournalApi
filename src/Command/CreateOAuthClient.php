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
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(name: 'app:oauth:client:create')]
final class CreateOAuthClient extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                name: 'name',
                mode: InputArgument::REQUIRED,
                description: 'The user friendly name of the client (app)',
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $clientId = Uuid::v4();
        $clientSecret = bin2hex(random_bytes(32));

        $client = (new OAuthClient())
            ->setIdentifier($clientId)
            ->setName($input->getArgument('name'))
            ->setConfidential(true)
            ->setSecret($this->passwordHasher->hash($clientSecret))
        ;
        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $io->success([
            'Client successfully created!',
            "Make sure you save the client secret, it's encrypted in the database and cannot be retrieved.",
        ]);
        $io->table([
            'client_id',
            'client_secret',
        ], [
            [
                $clientId,
                $clientSecret,
            ],
        ]);

        return self::SUCCESS;
    }
}
