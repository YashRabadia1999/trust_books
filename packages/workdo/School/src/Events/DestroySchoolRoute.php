<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestroySchoolRoute
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $route;

    public function __construct($route)
    {
        $this->route = $route;
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
