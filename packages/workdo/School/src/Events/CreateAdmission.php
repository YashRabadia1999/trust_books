<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class CreateAdmission
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $admission;

    public function __construct($request,$admission)
    {
        $this->request   = $request;
        $this->admission = $admission;
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
