<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestoryTimetable
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $timetable;

    public function __construct($timetable)
    {
        $this->timetable = $timetable;
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
