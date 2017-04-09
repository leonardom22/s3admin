<?php

namespace App\Converter;

use App\Contract;

class Json implements Contract\Converter
{
    /**
     * @param array $data
     *
     * @return string
     */
    public static function fromArray($data)
    {
        return json_encode($data);
    }

    /**
     * @param string $data
     *
     * @return mixed
     */
    public static function toArray($data)
    {
        return json_decode($data, true);
    }
    
    public static function getContentType()
    {
        return 'application/json';
    }
}