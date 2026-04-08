<?php

namespace Workdo\DrivingSchool\Events;

use Illuminate\Queue\SerializesModels;

class UpdateDrivingTestHub
{
    use SerializesModels;

    public $request;
    public $test_hub;

    public function __construct($request ,$test_hub)
    {
        $this->request = $request;
        $this->test_hub = $test_hub;
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
