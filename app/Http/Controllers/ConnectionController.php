<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ConnectionController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $connections = $user->isAlumni()
            ? $user->receivedConnections()->with('mahasiswa.mahasiswaProfile')->latest()->get()
            : $user->sentConnections()->with('alumni.alumniProfile')->latest()->get();

        return view('connections.index', ['connections' => $connections]);
    }

    /** Mahasiswa mengirim permintaan koneksi ke alumni. */
    public function store(Request $request, User $alumni): RedirectResponse
    {
        abort_unless($alumni->isAlumni(), 404);

        $connection = Connection::firstOrNew([
            'mahasiswa_id' => $request->user()->id,
            'alumni_id' => $alumni->id,
        ]);

        if ($connection->exists && $connection->status !== 'rejected') {
            return back()->with('status', 'Permintaan koneksi sudah pernah dikirim.');
        }

        $connection->status = 'pending';
        $connection->save();

        UserNotification::send($alumni, 'connection', 'Permintaan koneksi baru',
            "{$request->user()->name} ingin terhubung dengan Anda.", route('connections.index'));

        return back()->with('status', 'Permintaan koneksi terkirim.');
    }

    /** Alumni menerima atau menolak permintaan koneksi. */
    public function update(Request $request, Connection $connection): RedirectResponse
    {
        abort_unless($connection->alumni_id == $request->user()->id, 403);

        $data = $request->validate(['status' => ['required', Rule::in(['accepted', 'rejected'])]]);
        $connection->update($data);

        UserNotification::send($connection->mahasiswa, 'connection',
            $data['status'] === 'accepted' ? 'Koneksi diterima' : 'Koneksi ditolak',
            "{$request->user()->name} " . ($data['status'] === 'accepted' ? 'menerima' : 'menolak') . ' permintaan koneksi Anda.',
            route('alumni.show', $request->user()));

        return back()->with('status', $data['status'] === 'accepted' ? 'Koneksi diterima.' : 'Koneksi ditolak.');
    }

    /** Mahasiswa membatalkan permintaan / alumni menghapus koneksi. */
    public function destroy(Request $request, Connection $connection): RedirectResponse
    {
        abort_unless(in_array($request->user()->id, [$connection->mahasiswa_id, $connection->alumni_id]), 403);

        $connection->delete();

        return back()->with('status', 'Koneksi dihapus.');
    }
}
