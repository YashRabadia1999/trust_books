<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestoryAdmission
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $admission;

    public function __construct($admission)
    {
        $this->admission = $admission;
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
