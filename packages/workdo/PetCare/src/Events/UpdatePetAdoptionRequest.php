<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class UpdatePetAdoptionRequest
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request; 
    public $petAdoptionRequest;

    public function __construct($request, $petAdoptionRequest)
    {
        $this->request = $request;
        $this->petAdoptionRequest = $petAdoptionRequest;
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
