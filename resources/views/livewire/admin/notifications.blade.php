<div
    class="admin-notifications"
    wire:poll.10s
>
    <div class="dropdown">
        <button
            type="button"
            class="header-action notification-button"
            title="Notifications"
            aria-label="Notifications"
            data-bs-toggle="dropdown"
            aria-expanded="false"
        >
            <i class="bi bi-bell"></i>

            @if ($this->unreadCount > 0)
                <span class="notification-badge">
                    {{ $this->unreadCount > 99 ? '99+' : $this->unreadCount }}
                </span>
            @endif
        </button>

        <div class="dropdown-menu dropdown-menu-end notifications-dropdown">
            <div class="notifications-header">
                <div>
                    <strong>Notifications</strong>
                    <small>{{ $this->unreadCount }} non lue(s)</small>
                </div>

                @if ($this->unreadCount > 0)
                    <button
                        type="button"
                        wire:click="markAllAsRead"
                        class="notifications-read-all"
                    >
                        Tout lire
                    </button>
                @endif
            </div>

            @forelse ($this->notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = is_null($notification->read_at);
                @endphp

                <div class="notification-item {{ $isUnread ? 'unread' : '' }}">
                    <div class="notification-icon">
                        <i class="bi bi-bag-check"></i>
                    </div>

                    <div class="notification-content">
                        <a
                            href="{{ $data['url'] ?? '#' }}"
                            wire:click="markAsRead('{{ $notification->id }}')"
                            class="notification-link"
                        >
                            <strong>{{ $data['title'] ?? 'Notification' }}</strong>
                            <span>{{ $data['message'] ?? '' }}</span>

                            @if (!empty($data['order_number']))
                                <small>Commande #{{ $data['order_number'] }}</small>
                            @endif

                            @if (!empty($data['customer_name']))
                                <small>Client : {{ $data['customer_name'] }}</small>
                            @endif

                            @if (isset($data['total']))
                                <small class="notification-total">
                                    {{ number_format((float) $data['total'], 0, ',', ' ') }} FCFA
                                </small>
                            @endif
                        </a>

                        <small class="notification-date">
                            {{ $notification->created_at->diffForHumans() }}
                        </small>
                    </div>

                    @if ($isUnread)
                        <span class="notification-unread-dot"></span>
                    @endif
                </div>
            @empty
                <div class="notifications-empty">
                    <div class="notifications-empty-icon">
                        <i class="bi bi-bell-slash"></i>
                    </div>

                    <strong>Aucune notification</strong>
                    <span>Les nouvelles commandes apparaîtront ici.</span>
                </div>
            @endforelse
        </div>
    </div>
</div>
