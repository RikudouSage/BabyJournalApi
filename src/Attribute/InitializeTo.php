<?php

namespace App\Attribute;

use Attribute;

/**
 * Used to initialize readonly properties
 *
 * @codeCoverageIgnore
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class InitializeTo
{
    public function __construct(
        public readonly mixed $value,
    ) {
    }
}
