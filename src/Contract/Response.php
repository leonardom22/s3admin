<?php

namespace App\Contract;

use App\Contract;
use App\Enum;

/**
 * Abstrata que define os tipos de resposta.
 * 
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
abstract class Response
{
    /** @var Enum\HttpStatusCode */
    protected $httpStatusCode;
    
    /**
     * Define código http de resposta.
     * 
     * @param Enum\HttpStatusCode $httpStatusCode
     * 
     * @return Response
     */
    public function setHttpStatusCode(Enum\HttpStatusCode $httpStatusCode)
    {
        $this->httpStatusCode = $httpStatusCode;
        
        return $this;
    }

    /**
     * Retorna código http de resposta.
     *
     * @return Enum\HttpStatusCode
     */
    public function getHttpStatusCode()
    {
        if (empty($this->httpStatusCode)) {
            return Enum\HttpStatusCode::OK();
        }
        
        return $this->httpStatusCode;
    }

    /**
     * Retorna o conteúdo da resposta.
     *
     * @param Contract\Converter $converter
     *
     * @return string
     */
    public abstract function getContent($converter);
}