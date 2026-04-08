<?php

namespace Workdo\DrivingSchool\Events;

use Illuminate\Queue\SerializesModels;

class DestroyDrivingLicenceTraking
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $licence_traking;
    public function __construct($licence_traking)
    {
        $this->licence_traking = $licence_traking;
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
