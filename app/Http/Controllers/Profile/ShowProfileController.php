<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ShowProfileController extends Controller
{
    public function __invoke(User $user): Response
    {

        $profile = $user->only(['day_of_birth', 'gender','phone', 'profile_image']);

        return new JsonResponse(
            data: [
                'message' => 'Profile retrieved successfully',
                'data' => $profile,
            ],
            status: Response::HTTP_OK,
        );
    }
}
