<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class UpdatePetCareFAQ
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
        $this->request;
        $this->faq;
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
