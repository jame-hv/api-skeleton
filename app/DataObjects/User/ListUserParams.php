<?php

declare(strict_types=1);

namespace App\DataObjects\User;

final class ListUserParams
{
    public function __construct(
        public readonly ?string $keyword = null,
        public readonly ?string $sortBy = null,
        public readonly ?string $sortDirection = null,
        public readonly ?int $page = 1,
        public readonly ?int $perPage = 10,
        public readonly ?array $filters = [],
        public readonly ?array $dateRanges = [],
    ) {
    }


    public function toArray(): array
    {
        return [
            'keyword' => $this->keyword,
            'sortBy' => $this->sortBy ?? 'created_at',
            'sortDirection' => $this->sortDirection ?? 'desc',
            'page' => $this->page ?? 1,
            'perPage' => $this->perPage ?? 10,
            'filters' => $this->filters ?? [],
            'date_ranges' => $this->dateRanges ?? [],
        ];
    }
}
