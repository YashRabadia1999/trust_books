<?php

namespace Workdo\PettyCashManagement\Events;

use Illuminate\Queue\SerializesModels;

class UpdatePettyCashRequest
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $pettyCashRequest;
    
    public function __construct($request,$pettyCashRequest)
    {
        $this->request = $request;
        $this->pettyCashRequest = $pettyCashRequest;
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
