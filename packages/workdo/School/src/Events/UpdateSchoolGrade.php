<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class UpdateSchoolGrade
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
  public $request;
    public $grade;
    public function __construct($request,$grade)
    {
        $this->request   = $request;
        $this->grade     = $grade;
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
