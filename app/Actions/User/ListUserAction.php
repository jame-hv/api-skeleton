<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Concerns\Filters\FilterableEntity;
use App\DTOs\User\ListUserParams;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ListUserAction
{
    use FilterableEntity;

    /**
     * Fields that can be searched with keyword
     */
    private array $searchableFields = [
        'name',
        'email',
        'phone',
    ];

    /**
     * Fields that can be used for sorting
     */
    private array $sortableFields = [
        'name',
        'email',
        'created_at',
    ];

    /**
     * Relations to eager load for performance
     */
    private array $eagerLoadRelations = [
        // Example: 'roles'
    ];

    /**
     * Related model fields that can be searched
     */
    private array $relationSearchFields = [
        // Example: 'roles' => ['name']
    ];



    /**
     * Handle the user listing request
     *
     * @param ListUserParams $listUserParams DTO containing request parameters
     * @return LengthAwarePaginator Paginated list of users
     */
    public function handle(ListUserParams $listUserParams): LengthAwarePaginator
    {
        $params = $listUserParams->toArray();
        $query = User::query();

        // Apply eager loading
        if ( ! empty($this->eagerLoadRelations)) {
            $this->applyEagerLoading($query, $this->eagerLoadRelations);
        }

        // Apply keyword search on main fields
        if ( ! empty($params['keyword'])) {
            $this->applySearch($query, $params['keyword'], $this->searchableFields);

            // Extend search to related models
            if ( ! empty($this->relationSearchFields)) {
                $this->applyRelationSearch($query, $params['keyword'], $this->relationSearchFields);
            }
        }

        // Apply exact match filters
        if ( ! empty($params['filters'])) {
            $this->applyFilters($query, $params['filters']);
        }

        // Apply date range filters
        if ( ! empty($params['date_ranges'])) {
            $this->applyDateRangeFilters($query, $params['date_ranges']);
        }

        // Sort results
        $this->applySorting(
            $query,
            $params['sortBy'],
            $params['sortDirection'],
            $this->sortableFields,
        );

        // Return paginated results
        return $this->applyPagination(
            $query,
            $params['perPage'],
            $params['page'],
        );
    }
}
