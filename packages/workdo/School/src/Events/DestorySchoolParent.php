<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestorySchoolParent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $parent;

    public function __construct($parent)
    {
        $this->parent = $parent;
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
