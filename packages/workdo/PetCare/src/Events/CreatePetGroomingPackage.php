<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class CreatePetGroomingPackage
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    
    public $request;
    public $package;
    public $servicesData;
    public $vaccinesData;

    public function __construct($request, $package, $servicesData, $vaccinesData)
    {
        $this->request = $request;
        $this->package = $package;
        $this->servicesData = $servicesData;
        $this->vaccinesData = $vaccinesData;
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
