<?php

namespace App\Notifications\Dashboard;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class ReportError extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private $date, private $message)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable): TelegramMessage
    {

        return TelegramMessage::create()
            ->to(config('custom.TELEGRAM_CHAT_ID'))
            ->line("😵‍💫 ВНИМАНИЕ")
            ->line("")
            ->line("Суточный отчет по $this->date не создан!")
            ->line("$this->message");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
