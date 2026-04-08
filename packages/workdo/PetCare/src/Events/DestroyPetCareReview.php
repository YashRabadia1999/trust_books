<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPetCareReview
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $petCareReview;
    public function __construct($petCareReview)
    {
        $this->petCareReview = $petCareReview;
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
