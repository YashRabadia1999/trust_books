<?php

namespace Workdo\BulkSMS\Events;

use Illuminate\Queue\SerializesModels;
use Workdo\BulkSMS\Entities\CustomerMessage;

class DestroyCustomerMessage
{
    use SerializesModels;

    public $customerMessage;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CustomerMessage $customerMessage)
    {
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
