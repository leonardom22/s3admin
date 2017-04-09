<?php

namespace App\Core;

use App\Contract;

/**
 * Class Storage
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Storage
{
    /**
     * @param string $file
     * @param string $content
     *
     * @return bool
     */
    public static function save($file, $content)
    {
        if (!is_dir(self::getPathDefault())) {
            mkdir(self::getPathDefault());
        }

        $file = self::getPathDefault() . DS . $file;

        $handle = fopen($file, 'w');

        if ($handle == false) {
            return false;
        }

        fwrite($handle, $content);
        fclose($handle);

        chmod($file, 0777);

        return true;
    }

    /**
     * @param string $file
     * @param Contract\Converter|null $converter
     *
     * @return mixed
     */
    public static function retrieve($file, Contract\Converter $converter = null)
    {
        $path = self::getPathDefault() . DS . $file;

        $content = @file_get_contents($path);

        if (empty($content)) {
            return null;
        }

        if (!empty($converter)) {
            return $converter::toArray($content);
        }

        return $content;
    }

    /**
     * @return string
     */
    public static function getPathDefault()
    {
        return Config::getAppDir() . DS . 'storage';
    }
}