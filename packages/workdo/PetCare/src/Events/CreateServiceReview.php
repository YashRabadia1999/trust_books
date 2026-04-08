<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class CreateServiceReview
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    
    public $request;
    public $serviceReview;

    public function __construct($request, $serviceReview)
    {
        $this->request = $request;
        $this->serviceReview = $serviceReview;
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
