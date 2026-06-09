<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Tampilkan semua notifikasi user
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderByDesc('id')
            ->paginate(10);

        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('status', 'unread')
            ->count();

        return view('user.notifications.index', compact(
            'notifications',
            'unreadCount'
        ));
    }

    /**
     * Tandai semua notifikasi dibaca
     */
    public function readAll()
    {
        Notification::where('user_id', Auth::id())
            ->where('status', 'unread')
            ->update([
                'status' => 'read',
            ]);

        return back()->with(
            'success',
            'Semua notifikasi telah dibaca.'
        );
    }

    /**
     * Tandai 1 notifikasi dibaca
     */
    public function markAsRead($id)
    {
        Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->update([
                'status' => 'read',
            ]);

        return back();
    }

    /**
     * Hapus notifikasi
     */
    public function destroy($id)
    {
        Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with(
            'success',
            'Notifikasi berhasil dihapus.'
        );
    }

    /**
     * Endpoint polling AJAX
     */
    public function poll()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderByDesc('id')
            ->take(10)
            ->get()
            ->map(function ($n) {
                $data = is_array($n->data)
                    ? $n->data
                    : (json_decode($n->data ?? '[]', true) ?: []);

                return [
                    'id'         => $n->id,
                    'type'       => $n->type ?: ($data['type'] ?? 'system'),
                    'title'      => $n->display_title,
                    'message'    => $n->display_message,
                    'data'       => $data,
                    'status'     => $n->status,
                    'is_unread'  => $n->status === 'unread',
                    'time'       => $n->created_at ? $n->created_at->diffForHumans() : '-',
                    'created_at' => $n->created_at ? $n->created_at->toISOString() : null,
                ];
            });

        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('status', 'unread')
            ->count();

        $total = Notification::where('user_id', Auth::id())
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
            'total'         => $total,
        ]);
    }
}