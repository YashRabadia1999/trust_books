<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPetAppointment
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $petAppointment;

    public function __construct($petAppointment)
    {
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
