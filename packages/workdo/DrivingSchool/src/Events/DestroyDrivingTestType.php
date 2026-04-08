<?php

namespace Workdo\DrivingSchool\Events;

use Illuminate\Queue\SerializesModels;

class DestroyDrivingTestType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $test_type;
    public function __construct($test_type)
    {
        $this->test_type = $test_type;
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
