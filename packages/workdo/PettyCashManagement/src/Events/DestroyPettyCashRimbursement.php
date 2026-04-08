<?php

namespace Workdo\PettyCashManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPettyCashRimbursement
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
     public $reimbursement;
    public function __construct($reimbursement)
    {
        $this->reimbursement = $reimbursement;
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
