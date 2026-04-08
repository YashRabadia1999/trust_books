<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestroySchoolRoom
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $room;

    public function __construct($room)
    {
        $this->room = $room;
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
