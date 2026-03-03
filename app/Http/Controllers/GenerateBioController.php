<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GenerateBioAction;
use App\Http\Requests\GenerateBioRequest;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;

class GenerateBioController extends Controller
{
    public function __invoke(GenerateBioRequest $request, Profile $profile, GenerateBioAction $action): JsonResponse
    {
        abort_if($profile->user_id !== $request->user()->id, 403);

        $bio = $action($profile, $request->validated('context'));

        return response()->json(['bio' => $bio]);
    }
}
