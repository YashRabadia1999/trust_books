<?php

namespace Workdo\DrivingSchool\Events;

use Illuminate\Queue\SerializesModels;

class UpdateDrivingStudent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $student;
    
    public function __construct($request, $student)
    {
        $this->request = $request;
        $this->$student  = $student;
    }
}
