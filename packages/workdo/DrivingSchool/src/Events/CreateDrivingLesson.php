<?php

namespace Workdo\DrivingSchool\Events;

use Illuminate\Queue\SerializesModels;

class CreateDrivingLesson
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $lesson;

    public function __construct($lesson)
    {
        $this->lesson = $lesson;
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
