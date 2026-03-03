<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    public function index(): Response
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->limit(50)
            ->get();

        return Inertia::render('notifications/index', [
            'notifications' => NotificationResource::collection($notifications),
        ]);
    }

    public function markRead(string $id): RedirectResponse
    {
        auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();

        return redirect()->back();
    }

    public function markAllRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
