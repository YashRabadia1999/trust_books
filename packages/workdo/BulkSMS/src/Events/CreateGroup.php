<?php

namespace Workdo\BulkSMS\Events;

use Illuminate\Queue\SerializesModels;

class CreateGroup
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $bulksmsGroup;
    
    public function __construct($request, $bulksmsGroup)
    {
        $this->request = $request;
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
