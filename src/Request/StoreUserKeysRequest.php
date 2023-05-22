<?php

namespace App\Request;

use App\Attribute\RequestDTO;

#[RequestDTO]
final class StoreUserKeysRequest
{
    public string $keys;
}
