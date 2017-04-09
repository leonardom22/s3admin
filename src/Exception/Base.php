<?php

namespace App\Exception;

use App\Enum;

class Base extends \Exception
{
    public $httpStatusCode;
    public $causes;
    
    public function __construct($message = null, Enum\HttpStatusCode $httpStatusCode = null, $causes = [])
    {
        $this->message = $message;
        $this->httpStatusCode = $httpStatusCode;
        $this->causes = $causes;
    }

    /**
     * @return Enum\HttpStatusCode
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * @return array
     */
    public function getCauses()
    {
        return $this->causes;
    }
}