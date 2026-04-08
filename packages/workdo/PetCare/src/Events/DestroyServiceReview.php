<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class DestroyServiceReview
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $serviceReview;

    public function __construct($serviceReview)
    {
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
