<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class UpdatePetService
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $petService;

    public function __construct($request, $petService)
    {
        $this->request = $request;
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
