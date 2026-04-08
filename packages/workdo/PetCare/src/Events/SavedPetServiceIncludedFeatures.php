<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class SavedPetServiceIncludedFeatures
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

     public $request;
     public $includedFeatures;
     public $petService;
 
     public function __construct($request,$includedFeatures,$petService)
     {
         $this->request = $request;
         $this->includedFeatures = $includedFeatures;
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
