<?php

namespace Workdo\BulkSMS\Events;

use Illuminate\Queue\SerializesModels;

class CreateContact
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $bulksmsContact;
    
    public function __construct($request, $bulksmsContact)
    {
        $this->request = $request;
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
