<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPetVaccine
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $petVaccine;
    public function __construct($petVaccine)
    {
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
