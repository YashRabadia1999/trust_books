<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class CreatePetVaccine
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    Public $request;
    Public $petVaccine;
    public function __construct($request , $petVaccine)
    {
        $this->request = $request;
        $this->petVaccine = $petVaccine;
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
