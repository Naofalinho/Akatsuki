<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Notifications extends Component
{
    public function getNotificationsProperty()
    {
        $this->authorizeAdmin();

        return auth()->user()
            ->notifications()
            ->latest()
            ->limit(10)
            ->get();
    }

    public function getUnreadCountProperty(): int
    {
        $this->authorizeAdmin();

        return auth()->user()
            ->unreadNotifications()
            ->count();
    }

    public function markAsRead(string $id): void
    {
        $this->authorizeAdmin();

        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead(): void
    {
        $this->authorizeAdmin();

        auth()->user()
            ->unreadNotifications()
            ->update([
                'read_at' => now(),
            ]);
    }

    public function render()
    {
        return view('livewire.admin.notifications');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }
}
