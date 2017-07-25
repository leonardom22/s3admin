<?php

namespace App\Core;

use App\Contract;

/**
 * Classe responsável por manipular os arquivos da aplicação.
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Storage
{
    /**
     * Salva um arquivo.
     *
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
     * Retorna o conteúdo de um arquivo.
     *
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
     * Cria uma pasta.
     *
     * @param string $folder
     *
     * @return string
     */
    public static function createFolder($folder)
    {
        $path = self::getPathDefault() . DS . $folder . DS;

        if (!is_dir($path)) {
            mkdir($path);
        }

        return $path;
    }

    /**
     * Retorna o caminho padrão.
     *
     * @return string
     */
    public static function getPathDefault()
    {
        return Config::getAppDir() . DS . 'storage';
    }
}