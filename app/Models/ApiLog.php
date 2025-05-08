<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiLog extends Model
{
    use HasUlids;

    protected $fillable = [
        'request_id',
        'method',
        'url',
        'status',
        'time',
        'request',
        'response',
        'token',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
        );
    }

    protected function casts(): array
    {
        return [
            'status' => 'integer',
            'time' => 'integer',
            'request' => 'json',
            'response' => 'json',
            'token' => 'encrypted',
        ];
    }

}
