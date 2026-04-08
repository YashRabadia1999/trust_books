<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class UpdatePetGroomingPackage
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $petGroomingPackage;
    public $servicesData;
    public $vaccinesData;

    public function __construct($request, $petGroomingPackage, $servicesData, $vaccinesData)
    {
        $this->request = $request;
        $this->petGroomingPackage = $petGroomingPackage;
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
