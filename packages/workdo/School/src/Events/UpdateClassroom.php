<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class UpdateClassroom
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $classroom;

    public function __construct($request,$classroom)
    {
        $this->request   = $request;
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
