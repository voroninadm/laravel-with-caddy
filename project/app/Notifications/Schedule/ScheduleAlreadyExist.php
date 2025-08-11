<?php

namespace App\Notifications\Schedule;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class ScheduleAlreadyExist extends Notification
{
    use Queueable;

    private $scheduleDate;

    /**
     * Create a new notification instance.
     */
    public function __construct($scheduleDate)
    {
        $this->scheduleDate = $scheduleDate;
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
            ->line("Сбой графика распоряжений!")
            ->line("График распоряжений уже содержит запись за {$this->scheduleDate}!");
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
