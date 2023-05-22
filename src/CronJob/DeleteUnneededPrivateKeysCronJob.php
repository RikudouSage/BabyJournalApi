<?php

namespace App\CronJob;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Rikudou\CronBundle\Cron\CronJobInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final readonly class DeleteUnneededPrivateKeysCronJob implements CronJobInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getCronExpression(): string
    {
        return '0 * * * *';
    }

    public function execute(InputInterface $input, OutputInterface $output, ?LoggerInterface $logger): void
    {
        foreach ($this->userRepository->findAll() as $user) {
            if (!$user->hasApplicationsConnected() && $user->getEncryptionKey() !== null) {
                $user->setEncryptionKey(null);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $output->writeln("Deleting encryption key for user '{$user->getId()}'");
            }
        }
    }
}
