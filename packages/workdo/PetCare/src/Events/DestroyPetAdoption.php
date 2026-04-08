<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPetAdoption
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $petAdoption;
    public function __construct($petAdoption)
    {
        $this->petAdoption = $petAdoption;
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
