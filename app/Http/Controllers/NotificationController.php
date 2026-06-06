<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $notifications,
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$notification) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Notifikasi tidak ditemukan',
            ], 404);
        }

        $notification->update(['is_read' => true]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Notifikasi ditandai sudah dibaca',
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->update(['is_read' => true]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Semua notifikasi ditandai sudah dibaca',
        ]);
    }
}