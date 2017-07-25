<?php

namespace App\Core;

use App\Converter\Json;
use App\Enum;

/**
 * Classe respons�vel por resgatar as informa��es das requisi��es.
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Request
{
    /** @var array */
    public static $headers;
    
    /** @var Enum\HttpMethod */
    public static $method;
    
    /** @var array */
    public static $body;

    /**
     * Retorna o conte�do de um header.
     *
     * @param string $name
     *
     * @return string|null
     */
    public static function getHeader($name)
    {
        $headers = self::getHeaders();
        
        if (!empty($headers[$name])) {
            return $headers[$name];
        }
        
        return null;
    }

    /**
     * Retorna todos os headers.
     *
     * @return array|false
     */
    public static function getHeaders()
    {
        if (empty(self::$headers)) {
            self::$headers = getallheaders();
        }
        
        return self::$headers;
    }

    /**
     * Retorna o m�todo da requisi��o.
     *
     * @return Enum\HttpMethod
     */
    public static function getMethod()
    {
        if (!empty(self::$method)) {
            return self::$method;
        }
        
        try {
            self::$method = Enum\HttpMethod::memberByValue($_SERVER['REQUEST_METHOD']);    
        } catch (\Exception $e) {
            self::$method = Enum\HttpMethod::GET();
        }
        
        return self::$method;
    }

    /**
     * Retorna o conte�do da requisi��o.
     *
     * @return array
     */
    public static function getBody()
    {
        if (!empty(self::$body)) {
            return self::$body;
        }

        $body = file_get_contents('php://input');

        switch (self::getHeader('Content-Type')) {
            case Json::getContentType():
            default:
                self::$body = Json::toArray($body);
        }
        
        return self::$body;
    }

    /**
     * Efetua a valida��o do conte�do da requisi��o.
     *
     * @param array $fields
     * @param array|null $body
     *
     * @return bool
     */
    public static function validate($fields, array $body = null)
    {
        foreach ($fields as $field) {
            if (!isset($body[$field])) {
                return $field;
            }
        }

        return false;
    }
}