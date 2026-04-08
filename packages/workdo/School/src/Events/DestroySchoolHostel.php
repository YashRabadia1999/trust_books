<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestroySchoolHostel
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $hostel;

    public function __construct($hostel)
    {
        $this->hostel = $hostel;
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
