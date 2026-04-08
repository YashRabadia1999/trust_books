<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class CreatePetAppointment
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $appointment;
    public $owner;
    public $pet;
    public $services;
    public $packages;

    public function __construct($request, $appointment, $owner, $pet, $services = [], $packages = [])
    {
        $this->request = $request;
        $this->appointment = $appointment;
        $this->$owner = $owner;
        $this->pet = $pet;
        $this->services    = $services;
        $this->packages    = $packages;
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
