<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PhotoController extends Controller
{
    public function show(User $user): StreamedResponse
    {
        abort_unless($user->profile_photo && Storage::disk('local')->exists($user->profile_photo), 404);

        return Storage::disk('local')->response($user->profile_photo);
    }
}
