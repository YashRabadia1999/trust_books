<?php

namespace Workdo\BulkSMS\Events;

use Illuminate\Queue\SerializesModels;

class DestoryContact
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $bulksmsContact;
    
    public function __construct($bulksmsContact)
    {
        $this->bulksmsContact = $bulksmsContact;
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
