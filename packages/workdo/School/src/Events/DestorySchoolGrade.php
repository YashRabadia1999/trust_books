<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestorySchoolGrade
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $grade;
    public function __construct($grade)
    {
        $this->grade = $grade;
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
