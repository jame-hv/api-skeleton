<?php

declare(strict_types=1);

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
final readonly class DescriptionAttribute
{
    public function __construct(
        public string $description,
    ) {
    }
}
