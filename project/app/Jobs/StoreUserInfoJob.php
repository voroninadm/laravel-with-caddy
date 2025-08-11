<?php

namespace App\Jobs;

use App\Models\Info;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class StoreUserInfoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $sector_users;
    private $message;

    public function __construct($sector_users, $message)
    {
        $this->sector_users = $sector_users;
        $this->message = $message;
    }

    /**
     * @throws Throwable
     */
    public function handle(): void
    {
        foreach ($this->sector_users as $user) {
            DB::transaction(function () use ($user) {
                $this->message->relatedInfo()->create([
                    'info_id' => $this->message->id,
                    'user_id' => $user->id,
                    'sector_id' => $this->message->sector_id,
                    'is_read' => false
                ]);
            });
        }
    }

    public function failed(Throwable $exception): void
    {
        Log::error("StoreUserInfoJob failed", [
            'exception' => $exception,
            'sector_users_count' => count($this->sector_users),
            'current_user_id' => auth()->id()
        ]);
    }
}


