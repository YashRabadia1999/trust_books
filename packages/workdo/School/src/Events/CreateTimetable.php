<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class CreateTimetable
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $timetable;

    public function __construct($request,$timetable)
    {
        $this->request   = $request;
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
