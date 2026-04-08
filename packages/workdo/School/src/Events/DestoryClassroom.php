<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestoryClassroom
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $classroom;

    public function __construct($classroom)
    {
        $this->classroom = $classroom;
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
