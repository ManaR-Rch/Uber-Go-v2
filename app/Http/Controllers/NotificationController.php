<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
                                     ->orderBy('created_at', 'desc')
                                     ->get();
        return response()->json($notifications);
    }

    /**
     * Create a new notification.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'message' => 'required|string'
        ]);

        $notification = Notification::create([
            'user_id' => Auth::id(),
            'message' => $validatedData['message'],
            'lue' => false
        ]);

        return response()->json($notification, 201);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);

        // Ensure the user can only mark their own notifications
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->update(['lue' => true]);

        return response()->json($notification);
    }

    /**
     * Get unread notifications count.
     */
    public function unreadCount()
    {
        $unreadCount = Notification::where('user_id', Auth::id())
                                   ->where('lue', false)
                                   ->count();

        return response()->json(['unread_count' => $unreadCount]);
    }

    /**
     * Delete a specific notification.
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);

        // Ensure the user can only delete their own notifications
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json(['message' => 'Notification supprimée avec succès']);
    }

    /**
     * Delete all read notifications.
     */
    public function clearRead()
    {
        Notification::where('user_id', Auth::id())
                    ->where('lue', true)
                    ->delete();

        return response()->json(['message' => 'Notifications lues supprimées avec succès']);
    }
}