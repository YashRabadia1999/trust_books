<?php

namespace Workdo\DrivingSchool\Events;

use Illuminate\Queue\SerializesModels;

class DestoryDrivingClass
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $class;
    public function __construct($class)
    {
        $this->class = $class;
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
