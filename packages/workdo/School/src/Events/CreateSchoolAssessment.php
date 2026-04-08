<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class CreateSchoolAssessment
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $assessment;

    public function __construct($request, $assessment)
    {
        $this->request = $request;
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
