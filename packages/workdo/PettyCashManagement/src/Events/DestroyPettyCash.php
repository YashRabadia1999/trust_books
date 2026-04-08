<?php

namespace Workdo\PettyCashManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPettyCash
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $pattycash;
    public function __construct($pattycash)
    {
        $this->pattycash = $pattycash;
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
