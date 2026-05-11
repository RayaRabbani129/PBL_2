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
        $notifications = Notification::where(
                'user_id',
                Auth::id()
            )
            ->latest()
            ->paginate(15);

        $unreadCount = Notification::where(
                'user_id',
                Auth::id()
            )
            ->where('status', 'unread')
            ->count();

        return view(
            'user.notifications.index',
            compact(
                'notifications',
                'unreadCount'
            )
        );
    }

    /**
     * Tandai semua dibaca
     */
    public function readAll()
    {
        Notification::where(
                'user_id',
                Auth::id()
            )
            ->where('status', 'unread')
            ->update([
                'status' => 'read'
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
                'status' => 'read'
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
     * Endpoint polling AJAX — return JSON notif terbaru
     */
    public function poll()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->take(15)
            ->get()
            ->map(function ($n) {
                return [
                    'id'         => $n->id,
                    'type'       => $n->type,
                    'title'      => $n->title,
                    'message'    => $n->message,
                    'data'       => $n->data,
                    'status'     => $n->status,
                    'is_unread'  => $n->status === 'unread',
                    'time'       => $n->created_at->diffForHumans(),
                    'created_at' => $n->created_at->toISOString(),
                ];
            });

        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('status', 'unread')
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }
}