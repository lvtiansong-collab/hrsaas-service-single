<?php

namespace Wiltechsteam\HrsaasServiceSingle\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PositionAddedEvent
{
    use Dispatchable, SerializesModels;

    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
}
