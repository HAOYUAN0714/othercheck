<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CatchException
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $request;
    public $errorCode;
    public $errorMsg;

    /**
     * Create a new event instance.
     *
     * @param $request
     * @param $errorCode
     * @param $errorMsg
     */
    public function __construct($request, $errorCode, $errorMsg)
    {
        $this->request = $request;
        $this->errorCode = $errorCode;
        $this->errorMsg = $errorMsg;
    }
}
