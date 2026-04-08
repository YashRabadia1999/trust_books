<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class UpdateHealthRecord
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $record;
    
    public function __construct($request, $record)
    {
        $this->request = $request;
        $this->record = $record;
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
