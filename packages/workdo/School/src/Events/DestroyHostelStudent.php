<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestroyHostelStudent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $student;

    public function __construct($student)
    {
        $this->student = $student;
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
