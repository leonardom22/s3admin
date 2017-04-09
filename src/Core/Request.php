<?php

namespace App\Core;

use App\Converter\Json;
use App\Enum;

/**
 * Class Request
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
     * @param $name
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