<?php

namespace Workdo\PettyCashManagement\Events;

use Illuminate\Queue\SerializesModels;

class UpdatePettyCash
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $pattycash;
    
    public function __construct($request,$pattycash)
    {
        $this->request = $request;
        $this->pattycash = $pattycash;
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
