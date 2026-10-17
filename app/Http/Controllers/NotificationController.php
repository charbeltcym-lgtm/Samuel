<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();

        return view('samuel.admin.notifications', compact('notifications'));
    }

    /**
     * Endpoint JSON pour la cloche (AJAX).
     */
    public function latest(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->limit(15)
            ->get()
            ->map(fn ($n) => [
                'id' => $n->id,
                'titre' => $n->titre,
                'message' => $n->message,
                'type' => $n->type,
                'source' => $n->source,
                'lue' => $n->read_at !== null,
                'date' => $n->created_at->diffForHumans(),
            ]);

        return response()->json([
            'non_lues' => Notification::where('user_id', $request->user()->id)->whereNull('read_at')->count(),
            'notifications' => $notifications,
        ]);
    }

    public function markRead(Request $request, $id)
    {
        $notification = Notification::where('user_id', $request->user()->id)->findOrFail($id);
        $notification->update(['read_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Notification lue']);
        }

        return back();
    }

    public function markAllRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}
