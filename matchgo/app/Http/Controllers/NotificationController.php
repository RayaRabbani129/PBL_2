<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = DB::table('notifications')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $unreadCount = DB::table('notifications')
                ->where('user_id', Auth::id())
                ->whereIn('status', ['unread', 'sent'])
                ->count();

            return view('user.notifications.index', compact('notifications', 'unreadCount'));
        }

    public function readAll()
    {
        DB::table('notifications')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['unread', 'sent'])
            ->update(['status' => 'read']);

        return back()->with('success', 'Semua notifikasi telah dibaca.');
    }

    public function markAsRead($id)
    {
        DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->update(['status' => 'read']);

        return back();
    }

    public function destroy($id)
    {
        DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('success', 'Notifikasi dihapus.');
    }
}