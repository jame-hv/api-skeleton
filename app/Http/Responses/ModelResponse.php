<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

final readonly class ModelResponse implements Responsable
{
    /**
     * @param JsonResource $data The data to be returned in the response.
     * @param int $status The HTTP status code for the response. Default is 200 (OK).
     */
    public function __construct(
        private JsonResource $data,
        private int $status = Response::HTTP_OK,
    ) {
    }

    public function toResponse($request): Response
    {
        return new JsonResponse(
            data: $this->data,
            status: $this->status,
            headers: [],
        );
    }
}
