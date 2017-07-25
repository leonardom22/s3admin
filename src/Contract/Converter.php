<?php

namespace App\Contract;

/**
 * Interface que define os conversores.
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
     * @return array
     */
    public static function toArray($data);
}