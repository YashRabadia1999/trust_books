<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPetCareSocialLink
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $socialLink;

    public function __construct($socialLink)
    {
        $this->socialLink = $socialLink;
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
