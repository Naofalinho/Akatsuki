<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    public function __construct(
        public Order $order,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle commande ' . $this->order->order_number)
            ->greeting('Nouvelle commande reçue')
            ->line('Une nouvelle commande a été passée sur AKATSUKI.')
            ->line('Numéro : ' . $this->order->order_number)
            ->line('Client : ' . $this->order->customer_name)
            ->line('Montant total : ' . number_format((float) $this->order->total, 0, ',', ' ') . ' FCFA')
            ->action('Voir la commande', route('admin.commandes.show', $this->order))
            ->line('Connectez-vous à votre espace administrateur pour la traiter.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_name' => $this->order->customer_name,
            'total' => $this->order->total,
            'title' => 'Nouvelle commande reçue',
            'message' => 'Une nouvelle commande a été passée sur AKATSUKI.',
            'url' => route('admin.commandes.show', $this->order),
        ];
    }
}
