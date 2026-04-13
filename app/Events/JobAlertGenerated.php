<?php
// app/Events/JobAlertGenerated.php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobAlertGenerated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $userId;
    public $alert;
    public $jobsCount;
    
    /**
     * Create a new event instance.
     */
    public function __construct($userId, $alert, $jobsCount)
    {
        $this->userId = $userId;
        $this->alert = [
            'id' => $alert->id,
            'name' => $alert->name,
            'frequency' => $alert->frequency,
        ];
        $this->jobsCount = $jobsCount;
    }
    
    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }
    
    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'job.alert.generated';
    }
}