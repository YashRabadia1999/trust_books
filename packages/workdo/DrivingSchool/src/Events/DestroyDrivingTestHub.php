<?php

namespace Workdo\DrivingSchool\Events;

use Illuminate\Queue\SerializesModels;

class DestroyDrivingTestHub
{
    use SerializesModels;

    public $test_hub;

    public function __construct($test_hub)
    {
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
