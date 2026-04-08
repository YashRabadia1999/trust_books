<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class UpdateBookIssue
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $issue;

    public function __construct($request, $issue)
    {
        $this->request = $request;
        $this->issue   = $issue;
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
