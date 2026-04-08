<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestroySchoolNotice
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $notice;

    public function __construct($notice)
    {
        $this->notice = $notice;
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
