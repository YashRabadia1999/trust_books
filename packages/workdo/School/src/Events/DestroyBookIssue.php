<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestroyBookIssue
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $issue;

    public function __construct($issue)
    {
        $this->issue = $issue;
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
