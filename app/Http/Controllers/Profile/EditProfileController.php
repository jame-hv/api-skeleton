<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;

use App\Actions\Profile\EditProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class EditProfileController extends Controller
{
    public function __invoke(UpdateProfileRequest $request, EditProfileAction $action): Response
    {

        $status = $action->handle($request->payload());

        return new JsonResponse(
            data: [
                'message' => 'Profile updated successfully',
                'status' => $status,
            ],
            status: Response::HTTP_OK,
        );
    }
}
