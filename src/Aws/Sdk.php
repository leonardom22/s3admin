<?php

namespace App\Aws;

use App\Element;
use Aws\S3\S3Client;

/**
 * Class Sdk
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Sdk
{
    /** @var S3Client */
    public static $instance;

    /**
     * @param Element\Authentication|null $authentication
     *
     * @return S3Client
     */
    public static function getInstance(Element\Authentication $authentication = null)
    {
        if (!empty(self::$instance)) {
            return self::$instance;
        }

        self::$instance = new S3Client([
            'version' => 'latest',
            'region'  => $authentication->getRegion(),
            'credentials' => [
                'key' => $authentication->getKey(),
                'secret' => $authentication->getSecret(),
            ]
        ]);

        return self::$instance;
    }
}