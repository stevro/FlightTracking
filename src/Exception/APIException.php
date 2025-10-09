<?php

namespace Stevro\FlightTracking\Exception;

class APIException extends \Exception
{
    protected $details;

    public function __construct($message = '', $details = '', $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->details = $details;
    }

    /**
     * @return mixed|string
     */
    public function getDetails()
    {
        return $this->details;
    }
}