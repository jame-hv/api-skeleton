<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

final class MessageResponse implements Responsable
{
    // Common response for all controllers

    public function __construct(
        private string $message,
        private int $status = Response::HTTP_OK,
    ) {
    }

    public function toResponse($request): Response
    {
        return response()->json(
            data: [
                'message' => $this->message,
            ],
            status: $this->status,
            headers: [],
        );
    }

}
