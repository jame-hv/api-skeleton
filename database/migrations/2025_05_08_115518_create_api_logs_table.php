<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_logs', function (Blueprint $table): void {
            $table->ulid('id')->primary();

            $table->string('request_id')->unique();
            $table->string('method');
            $table->string('url');
            $table->unsignedInteger('status');
            $table->unsignedBigInteger('time')->default(0);

            $table->json('request')->nullable();
            $table->json('response')->nullable();

            $table->text('token')->nullable();

            $table
                ->foreignUlid('user_id')
                ->nullable()
                ->index()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
