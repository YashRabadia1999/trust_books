<?php

namespace Workdo\School\Events;

use Illuminate\Queue\SerializesModels;

class CreateSchoolFeesStructure
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $feeStructure;

    public function __construct($request , $feeStructure)
    {
        $this->request       = $request;
        $this->feeStructure  = $request;
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
