<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestroySchoolAlumini
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $alumini;

    public function __construct($alumini)
    {
        $this->alumini = $alumini;
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
