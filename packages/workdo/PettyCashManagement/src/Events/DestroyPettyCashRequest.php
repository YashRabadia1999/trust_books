<?php

namespace Workdo\PettyCashManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPettyCashRequest
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $pettyCashRequest;
    public function __construct($pettyCashRequest)
    {
        $this->pettyCashRequest = $pettyCashRequest;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
