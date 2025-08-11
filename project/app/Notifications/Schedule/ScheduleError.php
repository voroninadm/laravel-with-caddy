<?php

namespace App\Notifications\Schedule;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class ScheduleError extends Notification
{
    use Queueable;

    private $scheduleDate;
    private $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($scheduleDate, $message)
    {
        $this->scheduleDate = $scheduleDate;
        $this->message = $message;
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
            ->line("😵‍💫 ВНИМАНИЕ!")
            ->line("")
            ->line("График распоряжений за {$this->scheduleDate} не был получен!")
            ->line("{$this->message}");
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
