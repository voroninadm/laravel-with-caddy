<?php

namespace App\Events;

use App\Models\JsonOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BroadcastDailyScheduleCreatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public JsonOrder $order)
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('daily-schedule-created'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'daily-schedule-created';
    }
}
