<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class UpdateSchoolAlumini
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $alumini;
    
    public function __construct($request, $alumini)
    {
        $this->request = $request;
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
