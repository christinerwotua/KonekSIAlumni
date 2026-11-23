<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        return view('notifications.index', [
            'notifications' => $request->user()->inbox()->paginate(10),
        ]);
    }

    public function show(Request $request, UserNotification $notification): View
    {
        abort_unless($notification->user_id == $request->user()->id, 404);

        if (! $notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        return view('notifications.show', ['notification' => $notification]);
    }

    public function readAll(Request $request): RedirectResponse
    {
        $request->user()->inbox()->where('is_read', false)->update(['is_read' => true]);

        return back()->with('status', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
