<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

final readonly class PaginatedCollectionResponse implements Responsable
{
    public function __construct(
        private AnonymousResourceCollection $data,
        private int $status = Response::HTTP_OK,
    ) {
    }

    public function toResponse($request): Response
    {
        $paginator = $this->data->resource;

        // Get the transformed resource data
        $items = $this->data->toArray($request);

        if ($paginator instanceof LengthAwarePaginator) {
            $responseData = [
                'data' => $items,
                'meta' => [
                    'total' => $paginator->total(),
                    'per_page' => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'from' => $paginator->firstItem(),
                    'to' => $paginator->lastItem(),
                ],
                'links' => [
                    'first' => $paginator->url(1),
                    'last' => $paginator->url($paginator->lastPage()),
                    'prev' => $paginator->previousPageUrl(),
                    'next' => $paginator->nextPageUrl(),
                ],
            ];
        } else {
            // Handle non-paginated collections
            $responseData = [
                'data' => $items,
            ];
        }

        return new JsonResponse(
            data: $responseData,
            status: $this->status,
            headers: [],
        );
    }
}
