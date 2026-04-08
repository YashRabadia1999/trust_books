<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class CreateSchoolAssessmentResult
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $result;
    
    public function __construct($request, $result)
    {
        $this->request = $request;
        $this->result  = $result;
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
