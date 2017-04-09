<?php

namespace App\Core;

use App\Contract;
use App\Converter;

/**
 * Class Response
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Response
{
    /** @var Contract\Response */
    private $response;

    /**
     * Response constructor.
     * 
     * @param Contract\Response|null $response
     */
    public function __construct(Contract\Response $response = null)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        http_response_code($this->response->getHttpStatusCode()->value());
        header('Content-Type: ' . Converter\Json::getContentType());
        
        return $this->response->getContent(new Converter\Json);
    }

    /**
     * @param Contract\Response $response
     *
     * @return Response
     */
    public function setResponse(Contract\Response $response)
    {
        $this->response = $response;
        return $this;
    }
}