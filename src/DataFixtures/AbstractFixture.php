<?php

namespace App\DataFixtures;

use App\Entity\ParentalUnit;
use App\Repository\ParentalUnitRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use LogicException;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractFixture extends Fixture
{
    private ParentalUnitRepository $parentalUnitRepository;

    protected function encrypt(string $content): string
    {
        $key = file_get_contents(__DIR__ . '/../../config/dev_private_key/public.key');
        assert(is_string($key));

        openssl_public_encrypt($content, $encrypted, $key);

        return bin2hex($encrypted);
    }

    protected function decrypt(string $content): string
    {
        $content = hex2bin($content);
        $key = file_get_contents(__DIR__ . '/../../config/dev_private_key/private.key');

        assert(is_string($content));
        assert(is_string($key));

        openssl_private_decrypt($content, $decrypted, $key);

        return $decrypted;
    }

    protected function getParentalUnitByName(string $name): ParentalUnit
    {
        $units = $this->parentalUnitRepository->findAll();
        foreach ($units as $unit) {
            if ($this->decrypt((string) $unit->getName()) === $name) {
                return $unit;
            }
        }

        throw new LogicException("Parental unit with name '{$name}' not found");
    }

    #[Required]
    public function setParentalUnitRepository(ParentalUnitRepository $repository): void
    {
        $this->parentalUnitRepository = $repository;
    }
}
