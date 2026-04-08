<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class CreatePetCareFAQ
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request; 
    public $faq;

    public function __construct($request, $faq)
    {
        $this->request = $request;
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
