<?php

namespace App\Request;

use App\Attribute\InitializeTo;
use App\Attribute\RequestDTO;

#[RequestDTO]
final readonly class ModifyScopesRequest
{
    /**
     * @var array<string>
     */
    #[InitializeTo([])]
    public array $scopes;
}
