<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class CreateSchoolRoute
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $route;
    
    public function __construct($request, $route)
    {
        $this->request = $request;
        $this->route   = $route;
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
