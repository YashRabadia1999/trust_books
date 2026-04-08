<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class UpdateSubject
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
   public $request;
    public $subject;

    public function __construct($request,$subject)
    {
        $this->request   = $request;
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
