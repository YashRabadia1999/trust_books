<?php

namespace Workdo\BulkSMS\Events;

use Illuminate\Queue\SerializesModels;
use Workdo\BulkSMS\Entities\CustomerMessage;

class UpdateCustomerMessage
{
    use SerializesModels;

    public $request;
    public $customerMessage;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($request, CustomerMessage $customerMessage)
    {
        $this->request = $request;
        $this->customerMessage = $customerMessage;
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
