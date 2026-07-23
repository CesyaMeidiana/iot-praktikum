<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * JSON buat dropdown navbar (quick peek, 10 terbaru).
     */
    public function index()
    {
        $user = Auth::user();

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => $user->notifications()
                ->latest()
                ->take(10)
                ->get()
                ->map(fn ($n) => [
                    'id'      => $n->id,
                    'title'   => $n->data['title'],
                    'message' => $n->data['message'],
                    'url'     => route('mahasiswa.notifications.open', $n->id),
                    'is_read' => (bool) $n->read_at,
                    'time'    => $n->created_at->diffForHumans(),
                ]),
        ]);
    }

    /**
     * Halaman penuh dengan filter: all / unread / read.
     */
    public function page(Request $request)
    {
        $user   = Auth::user();
        $filter = $request->query('filter', 'all');

        $query = $user->notifications()->latest();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }

        return view('mahasiswa.notifications', [
            'notifications' => $query->paginate(15),
            'filter'        => $filter,
            'unreadCount'   => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Dipanggil via fetch() dari dropdown navbar (AJAX, return JSON).
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['url' => $notification->data['url']]);
    }

    /**
     * Dipanggil saat dropdown navbar DIBUKA (bukan saat item diklik).
     * Semua notif yang lagi kelihatan langsung dianggap "sudah dibaca".
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['status' => 'ok']);
    }

    /**
     * Dipanggil via klik link biasa (dari halaman penuh / notif lain) —
     * tandai dibaca lalu redirect langsung ke halaman terkait.
     */
    public function show($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect($notification->data['url']);
    }
}