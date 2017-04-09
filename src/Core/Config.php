<?php

namespace App\Core;

/**
 * Class Config
 * 
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Config
{
    /** @var string */
    public static $appDir;

    /**
     * @param $appDir
     */
    public static function setAppDir($appDir)
    {
        self::$appDir = $appDir;
    }

    /**
     * Retorna um arquivo de configuração.
     * 
     * @param string $file
     *
     * @return bool|mixed
     */
    public static function get($file)
    {
        $file = self::$appDir . DS . 'config' . DS . str_replace('.php', '', $file) . '.php';
        
        if (!file_exists($file)) {
            return false;
        }

        return require_once $file;
    }

    /**
     * @return string
     */
    public static function getAppDir()
    {
        return self::$appDir;
    }
}