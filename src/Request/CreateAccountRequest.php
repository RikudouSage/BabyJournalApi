<?php

namespace App\Request;

use App\Attribute\InitializeTo;
use App\Attribute\RequestDTO;
use Symfony\Component\Uid\Uuid;

#[RequestDTO]
final readonly class CreateAccountRequest
{
    #[InitializeTo(value: null)]
    public ?string $name;
    #[InitializeTo(value: null)]
    public ?string $parentalUnitName;

    #[InitializeTo(value: null)]
    public ?Uuid $parentalUnitId;
}
