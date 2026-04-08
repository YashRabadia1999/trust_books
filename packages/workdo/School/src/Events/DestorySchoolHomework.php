<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestorySchoolHomework
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $homework;

    public function __construct($homework)
    {
        $this->homework = $homework;
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
