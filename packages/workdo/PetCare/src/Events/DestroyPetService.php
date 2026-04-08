<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPetService
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $petService;
    public function __construct($petService)
    {
        $this->petService = $petService;
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
