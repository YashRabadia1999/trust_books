<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestorySubject
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $subject;

    public function __construct($subject)
    {
        $this->subject = $subject;
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
