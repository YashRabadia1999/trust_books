<?php

namespace Workdo\DrivingSchool\Events;

use Illuminate\Queue\SerializesModels;

class CreateDrivingLicenceType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $licence_type;

    public function __construct($request ,$licence_type)
    {
        $this->request = $request;
        $this->licence_type = $licence_type;

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
