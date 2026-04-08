<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class CreatePetAdoption
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $petAdoption;

    public function __construct($request, $petAdoption)
    {
        $this->request = $request;
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
