<?php

namespace Workdo\DrivingSchool\Events;

use Illuminate\Queue\SerializesModels;

class UpdateDrivingClass
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $class;

    public function __construct($request ,$class)
    {
        $this->request = $request;
        $this->class = $class;
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
