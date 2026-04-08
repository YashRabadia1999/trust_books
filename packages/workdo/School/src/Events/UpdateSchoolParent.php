<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class UpdateSchoolParent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $parent;

    public function __construct($request,$parent)
    {
        $this->request   = $request;
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
