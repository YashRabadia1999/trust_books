<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestroySchoolFeesStructure
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $feesStructure;

    public function __construct($feesStructure)
    {
        $this->feesStructure = $feesStructure;
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
