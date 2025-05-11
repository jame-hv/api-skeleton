<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use App\Http\Resources\DateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => [
                'address' => $this->resource->email,
                'verified' => $this->resource->hasVerifiedEmail(),
            ],
            'created' => new DateResource(
                resource: $this->resource->created_at,
            ),

        ];
    }
}
