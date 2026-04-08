<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class CreatePetAdoptionRequestPayment
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $petAdoptionRequestPayments;
    
    public function __construct($request, $petAdoptionRequestPayments)
    {
        $this->request = $request;
        $this->petAdoptionRequestPayments = $petAdoptionRequestPayments;
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
