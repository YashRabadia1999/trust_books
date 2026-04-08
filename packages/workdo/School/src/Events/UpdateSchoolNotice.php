<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class UpdateSchoolNotice
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $notice;
    
    public function __construct($request, $notice)
    {
        $this->request = $request;
        $this->notice  = $notice;
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
