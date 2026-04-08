<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class AdmissionConvert
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $admission;
    public $student;

    public function __construct($admission,$student)
    {
        $this->student   = $student;
        $this->admission = $admission;
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
