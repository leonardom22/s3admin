<?php

namespace App\Response;

use App\Contract;

class Error extends Contract\Response
{
    /** @var string */
    private $message;
    
    /** @var array */
    private $causes;

    /**
     * Error constructor.
     * 
     * @param $message
     */
    public function __construct($message = null)
    {
        $this->message = $message;
        $this->causes = [];
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     * @return Error
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return array
     */
    public function getCauses()
    {
        return $this->causes;
    }

    /**
     * @param array $causes
     *
     * @return Error
     */
    public function setCauses($causes)
    {
        $this->causes = $causes;
        return $this;
    }

    public function addCause($cause)
    {
        $this->causes[] = $cause;
        return $this;
    }

    public function getContent($converter)
    {
        $data = [
            'message' => $this->message,
            'status' => $this->getHttpStatusCode()->value(),
            'causes' => $this->causes
        ];
        
        return $converter->fromArray($data);
    }
}