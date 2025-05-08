<?php

declare(strict_types=1);

namespace App\Concerns\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

trait FilterableEntity
{
    /**
     * Apply basic search across specified model fields
     */
    protected function applySearch(Builder $query, string $keyword, array $searchableFields): void
    {
        $query->where(function ($q) use ($keyword, $searchableFields): void {
            foreach ($searchableFields as $index => $field) {
                if (0 === $index) {
                    $q->where($field, 'LIKE', "%{$keyword}%");
                } else {
                    $q->orWhere($field, 'LIKE', "%{$keyword}%");
                }
            }
        });
    }


    /**
     * Search across related models' fields
     * Format: ['relationName' => ['field1', 'field2']]
     */
    protected function applyRelationSearch(Builder $query, string $keyword, array $relationSearchFields): void
    {
        foreach ($relationSearchFields as $relation => $fields) {
            $query->orWhereHas($relation, function ($q) use ($keyword, $fields): void {
                $q->where(function ($subQ) use ($keyword, $fields): void {
                    foreach ($fields as $index => $field) {
                        $method = 0 === $index ? 'where' : 'orWhere';
                        $subQ->{$method}($field, 'LIKE', "%{$keyword}%");
                    }
                });
            });
        }
    }

    /**
     * Apply filters based on exact match criteria
     * Format: ['field' => 'value']
     */
    protected function applyFilters(Builder $query, array $filters): void
    {
        foreach ($filters as $field => $value) {
            if (null !== $value) {
                if (is_array($value)) {
                    $query->whereIn($field, $value);
                } else {
                    $query->where($field, $value);
                }
            }
        }
    }

    /**
     * Apply date range filters
     * Format: ['field' => ['from' => '2023-01-01', 'to' => '2023-12-31']]
     */
    protected function applyDateRangeFilters(Builder $query, array $dateRanges): void
    {
        foreach ($dateRanges as $field => $range) {
            if (isset($range['from'])) {
                $query->where($field, '>=', $range['from']);
            }

            if (isset($range['to'])) {
                $query->where($field, '<=', $range['to']);
            }
        }
    }

    /**
     * Apply sorting with support for relation fields
     * Format for sortable fields:
     * - Simple: ['name', 'email', 'created_at']
     * - With relation: ['customer_name' => ['relation' => 'customer', 'field' => 'name', 'table' => 'users']]
     */
    protected function applySorting(Builder $query, ?string $sortBy, ?string $sortDirection, array $sortableFields, string $defaultSortField = 'created_at'): void
    {
        // Determine if sortBy is a simple field or an array with relation info
        if (is_array($sortableFields)) {
            // Check if the sortBy is directly in the array (simple field)
            if (in_array($sortBy, $sortableFields)) {
                $validatedSortBy = $sortBy;
            }
            // Check if it's a key in the array (relation field)
            elseif (isset($sortableFields[$sortBy])) {
                $fieldDetails = $sortableFields[$sortBy];

                if (is_array($fieldDetails) && isset($fieldDetails['relation'], $fieldDetails['field'])) {
                    $this->joinRelationForSorting($query, $fieldDetails['relation'], $fieldDetails['field'], $fieldDetails['table'] ?? null);
                    $sortTable = $fieldDetails['table'] ?? $this->getRelationTableName($query, $fieldDetails['relation']);
                    $validatedSortBy = $sortTable . '.' . $fieldDetails['field'];
                } else {
                    $validatedSortBy = $sortBy;
                }
            } else {
                $validatedSortBy = $defaultSortField;
            }
        } else {
            $validatedSortBy = $defaultSortField;
        }

        // Validate sort direction
        $validatedSortDirection = in_array(mb_strtolower($sortDirection ?? ''), ['asc', 'desc'])
            ? mb_strtolower($sortDirection)
            : 'desc';

        $query->orderBy($validatedSortBy, $validatedSortDirection);
    }

    /**
     * Apply eager loading for improved performance
     */
    protected function applyEagerLoading(Builder $query, array $relations): void
    {
        if ( ! empty($relations)) {
            $query->with($relations);
        }
    }

    /**
     * Apply pagination to the query
     */
    protected function applyPagination(Builder $query, ?int $perPage = 10, ?int $page = 1)
    {
        return $query->paginate(
            perPage: $perPage ?? 10,
            page: $page ?? 1,
        );
    }

    /**
     * Join a relation table for sorting
     */
    private function joinRelationForSorting(Builder $query, string $relation, string $field, ?string $customTable = null): void
    {
        $model = $query->getModel();

        // Check if we already joined this table to avoid duplicate joins
        if ($this->isAlreadyJoined($query, $customTable ?? $relation)) {
            return;
        }

        if (method_exists($model, $relation)) {
            $relationObj = $model->{$relation}();
            $relatedTable = $customTable ?? $relationObj->getRelated()->getTable();

            if ($relationObj instanceof BelongsTo) {
                $foreignKey = $relationObj->getForeignKeyName();
                $ownerKey = $relationObj->getOwnerKeyName();

                $query->leftJoin(
                    $relatedTable,
                    $model->getTable() . '.' . $foreignKey,
                    '=',
                    $relatedTable . '.' . $ownerKey,
                );
            } elseif ($relationObj instanceof HasOneOrMany) {
                $foreignKey = $relationObj->getForeignKeyName();
                $localKey = $relationObj->getLocalKeyName();

                $query->leftJoin(
                    $relatedTable,
                    $model->getTable() . '.' . $localKey,
                    '=',
                    $relatedTable . '.' . $foreignKey,
                );
            }

            // Ensure we select the main model fields to avoid column conflicts
            $query->select($model->getTable() . '.*');
        }
    }

    /**
     * Check if a table is already joined to avoid duplicate joins
     */
    private function isAlreadyJoined(Builder $query, string $table): bool
    {
        $joins = $query->getQuery()->joins;

        if (null === $joins) {
            return false;
        }

        foreach ($joins as $join) {
            if ($join->table === $table) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the table name for a relation
     */
    private function getRelationTableName(Builder $query, string $relation): string
    {
        $model = $query->getModel();

        if (method_exists($model, $relation)) {
            return $model->{$relation}()->getRelated()->getTable();
        }

        return $relation;
    }
}
