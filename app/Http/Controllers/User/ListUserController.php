<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Actions\User\ListUserAction;
use App\DTOs\User\ListUserParams;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Responses\PaginatedCollectionResponse;
use Illuminate\Http\Request;

final class ListUserController extends Controller
{
    public function __invoke(Request $request, ListUserAction $action): PaginatedCollectionResponse
    {
        $filters = $request->input('filters', []);
        $dateRanges = $request->input('date_ranges', []);

        $listUserParams = new ListUserParams(
            keyword: $request->input('keyword'),
            sortBy: $request->input('sort_by'),
            sortDirection: $request->input('sort_direction'),
            page: (int) $request->input('page', 1),
            perPage: (int) $request->input('per_page', 10),
            filters: $filters,
            dateRanges: $dateRanges,
        );

        $data = $action->handle($listUserParams);

        return new PaginatedCollectionResponse(
            data: UserResource::collection($data),
        );

    }

}
