<?php

namespace Workdo\DrivingSchool\Events;

use Illuminate\Queue\SerializesModels;

class UpdateDrivingLesson
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $lessonData;

    public function __construct($lessonData)
    {
        $this->lessonData = $lessonData;
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
