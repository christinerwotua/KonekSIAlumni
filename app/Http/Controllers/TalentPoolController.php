<?php

namespace App\Http\Controllers;

use App\Models\TalentProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TalentPoolController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $search = trim((string) $request->query('q'));

        $talents = TalentProfile::query()
            ->with(['user.mahasiswaProfile'])
            ->where('is_visible', true)
            ->when($user->isMahasiswa(), fn ($q) => $q->where('user_id', '!=', $user->id))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('headline', 'like', "%{$search}%")
                        ->orWhere('skills', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest('updated_at')
            ->paginate(9)
            ->withQueryString();

        return view('talent.index', [
            'talents' => $talents,
            'myProfile' => $user->isMahasiswa() ? $user->talentProfile : null,
        ]);
    }

    public function show(Request $request, TalentProfile $talent): View
    {
        // Profil yang belum diaktifkan hanya bisa dilihat pemiliknya.
        abort_unless($talent->is_visible || $talent->user_id == $request->user()->id, 404);

        $talent->load(['user.mahasiswaProfile', 'documents']);

        return view('talent.show', ['talent' => $talent]);
    }
}
