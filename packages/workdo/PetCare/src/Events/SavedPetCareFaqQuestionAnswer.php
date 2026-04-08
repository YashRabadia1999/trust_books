<?php

namespace Workdo\PetCare\Events;

use Illuminate\Queue\SerializesModels;

class SavedPetCareFaqQuestionAnswer
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $submittedQueAnswers;
    public $faq;

    public function __construct($request, $submittedQueAnswers, $faq)
    {
        $this->$request = $request;
        $this->submittedQueAnswers; 
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
