<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Request;

/**
 * Handler exception for controller special for this project.
 */

class ControllerException extends Exception
{
    protected $message = "Something error at controller.";
    protected $code;
    const NoConfigDynamicComponent = 'Config for dynamic component is empty.';
    // const NoConfigDynamicComponent = 'Config for dynamic component is empty.';

    /**
     * Call controller exception.
     *
     * @param String $message Message for the errors. It good if you call with this instance.
     * - ControllerException::Error
     * @param integer $code Status code response for errors.
     */
    public function __construct(String $message, int $code)
    {
        $this->message = $message;
        $this->code = $code;
    }
    public function render(Request $request)
    {
        return response($this->message, $this->code);
    }
}
