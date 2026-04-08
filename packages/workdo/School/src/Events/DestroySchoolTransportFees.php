<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestroySchoolTransportFees
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $fee;

    public function __construct($fee)
    {
        $this->fee = $fee;
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
