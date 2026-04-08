<?php

namespace Workdo\DrivingSchool\Events;

use Illuminate\Queue\SerializesModels;

class DestroyDrivingProgressReport
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $progress_report;
    public function __construct($progress_report)
    {
        $this->progress_report = $progress_report;
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
