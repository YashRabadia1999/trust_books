<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPetGroomingPackage
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $petGroomingPackage;

    public function __construct($petGroomingPackage)
    {
        $this->petGroomingPackage = $petGroomingPackage;
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
