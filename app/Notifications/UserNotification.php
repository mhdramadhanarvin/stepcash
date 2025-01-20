<?php

namespace App\Notifications;

use App\Enums\NotificationEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as NotificationsFilament;

class UserNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $url;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $title, string $message, string $url = "")
    {
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        if ($this->url != '') {
            return NotificationsFilament::make()
                ->title($this->title)
                ->body($this->message)
                ->actions([
                    Action::make('lihat')
                        ->button()
                        ->url($this->url),
                ])
                ->getDatabaseMessage();
        }
        return NotificationsFilament::make()
            ->title($this->title)
            ->body($this->message)
            ->getDatabaseMessage();
    }
}
