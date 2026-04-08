<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class UpdatePetAppointment
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $owner;
    public $pet;
    public $petAppointment;
    
    public function __construct($request, $owner, $pet, $petAppointment)
    {
        $this->request = $request;
        $this->owner = $owner;
        $this->pet = $pet;
        $this->petAppointment = $petAppointment;
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
