<?php

declare(strict_types=1);

namespace App\Concerns\Rbac;

use ReflectionClassConstant;

/**
 * Trait for accessing attributes on backed enum cases.
 *
 * @template-requires BackedEnum
 * @mixin BackedEnum
 */
trait HasAttributes
{
    /**
     * The class name of the enum.
     *  @param class-string<TAttribute>
     */
    public function getAttributes(string $attributeClass): array
    {
        $reflection = new ReflectionClassConstant($this::class, $this->name);

        return array_map(
            static fn ($attribute) => $attribute-> NewInstance(),
            $reflection->getAttributes($attributeClass),
        );

    }

    public function getAttribute(string $attributeClass): ?object
    {
        return $this->getAttributes($attributeClass)[0] ?? null;
    }
}
