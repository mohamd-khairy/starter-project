<?php

namespace App\Notifications;

use App\Events\EventEvent;
use App\Events\RealTimeMessageEvent;
use App\Models\UserLocation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEventCreated extends Notification
{
    use Queueable;

    public $eventModel;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($eventModel)
    {
        $this->eventModel = $eventModel;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // mail
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $notificationData = [
            'user_id' => $notifiable->id,
            'event_id' => $this->eventModel->id,
            'title' => 'New event created',
            'subTitle' => 'New event From Type ' . implode(',', $this->eventModel->types) . ' created',
            'link' => '/models/show/' . $this->eventModel->location_id . '?id=' . $this->eventModel->id
        ];

        return $notificationData;
    }
}
