<?php

namespace App\Core;

use App\Contract;
use App\Converter;

/**
 * Classe responsável por montar a resposta para o cliente.
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Response
{
    /** @var Contract\Response */
    private $response;

    /**
     * @param Contract\Response|null $response
     */
    public function __construct(Contract\Response $response = null)
    {
        $this->response = $response;
    }

    /**
     * Retorna o conteúdo da resposta.
     *
     * @return string
     */
    public function getContent()
    {
        http_response_code($this->response->getHttpStatusCode()->value());
        header('Content-Type: ' . Converter\Json::getContentType());
        
        return $this->response->getContent(new Converter\Json);
    }

    /**
     * Define a resposta.
     *
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