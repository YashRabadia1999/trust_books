<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class DestroySchoolAssessment
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $assessment;

    public function __construct($assessment)
    {
        $this->assessment = $assessment;
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
