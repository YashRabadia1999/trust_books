<?php

namespace Workdo\DrivingSchool\Events;

use Illuminate\Queue\SerializesModels;

class DestoryDrivingVehicle
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $vehicle;

    public function __construct($vehicle)
    {
        $this->vehicle = $vehicle;
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
