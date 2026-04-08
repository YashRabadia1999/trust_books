<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestroyHealthRecord
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $record;

    public function __construct($record)
    {
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
