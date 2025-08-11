<?php

namespace App\Notifications\Encost;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class EncostRequestHasChangesNotification extends Notification
{
    use Queueable;

    public function __construct(public string $message)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable): TelegramMessage
    {

        return TelegramMessage::create()
            ->to(config('custom.TELEGRAM_CHAT_ID'))
            ->line("😵‍💫 ЭНКОСТ! 😵‍💫")
            ->line($this->message);
    }


    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
