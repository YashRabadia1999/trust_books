<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPetCareFAQ
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $faq;
    
    public function __construct($faq)
    {
        $this->faq = $faq;
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
