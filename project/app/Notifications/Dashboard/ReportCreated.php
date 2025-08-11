<?php

namespace App\Notifications\Dashboard;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class ReportCreated extends Notification
{
    use Queueable;

    public function __construct(private $date)
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

    /**
     * Get the mail representation of the notification.
     */
    public function toTelegram($notifiable): TelegramMessage
    {

        return TelegramMessage::create()
            ->to(config('custom.TELEGRAM_CHAT_ID'))
            ->line("🙂 Суточный отчет успешно создан")
            ->line("Суточный отчет обновлен по $this->date");
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
