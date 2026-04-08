<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class SavedPetServiceProcessSteps
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $processSteps;
    public $petService;

    public function __construct($request,$processSteps,$petService)
    {
        $this->request = $request;
        $this->processSteps = $processSteps;
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
