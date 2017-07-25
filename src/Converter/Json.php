<?php

namespace App\Converter;

use App\Contract;

/**
 * Conversor para Json.
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Json implements Contract\Converter
{
    /**
     * @inheritDoc
     * @return string
     */
    public static function fromArray($data)
    {
        return json_encode($data);
    }

    /**
     * @inheritDoc
     */
    public static function toArray($data)
    {
        return json_decode($data, true);
    }

    /**
     * Retorna o tipo de conteúdo http.
     *
     * @return string
     */
    public static function getContentType()
    {
        return 'application/json';
    }
}