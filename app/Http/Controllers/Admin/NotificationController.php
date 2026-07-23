<?php

namespace App\Http\Controllers\Admin;

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
                    'url'     => route('admin.notifications.open', $n->id),
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

        return view('admin.notifications', [
            'notifications' => $query->paginate(15),
            'filter'        => $filter,
            'unreadCount'   => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Dipanggil saat dropdown navbar DIBUKA. Semua notif yang lagi
     * kelihatan langsung dianggap "sudah dibaca".
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['status' => 'ok']);
    }

    /**
     * Klik notif -> tandai dibaca -> redirect ke halaman terkait.
     */
    public function show($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect($notification->data['url']);
    }
}