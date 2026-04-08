<?php

namespace Workdo\BulkSMS\Events;

use Illuminate\Queue\SerializesModels;

class DestoryGroup
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $bulksmsGroup;
    
    public function __construct($bulksmsGroup)
    {
        $this->bulksmsGroup = $bulksmsGroup;
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
