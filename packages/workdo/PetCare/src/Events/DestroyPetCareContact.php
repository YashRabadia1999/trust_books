<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPetCareContact
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $contact;

    public function __construct($contact)
    {
        $this->contact = $contact;
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
