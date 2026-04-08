<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class CreateSchoolBus
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $buses;
    
    public function __construct($request, $buses)
    {
        $this->request = $request;
        $this->buses   = $buses;
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
