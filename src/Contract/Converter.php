<?php

namespace App\Contract;

/**
 * Interface Converter
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
interface Converter
{
    /**
     * @param array $data
     *
     * @return mixed
     */
    public static function fromArray($data);

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public static function toArray($data);
}