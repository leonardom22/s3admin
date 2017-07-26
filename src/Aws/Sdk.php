<?php

namespace App\Aws;

use App\Element;
use Aws\CloudFront\CloudFrontClient;
use Aws\S3\S3Client;

/**
 * Class Sdk
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Sdk
{
    /** @var S3Client */
    public static $s3instance;

    /** @var CloudFrontClient */
    public static $cloudFrontInstance;

    /**
     * @param Element\Authentication|null $authentication
     *
     * @return S3Client
     */
    public static function getS3Instance(Element\Authentication $authentication = null)
    {
        if (!empty(self::$s3instance)) {
            return self::$s3instance;
        }

        self::$s3instance = new S3Client(
            self::buildAuthentication($authentication)
        );

        return self::$s3instance;
    }

    public static function getCloudFrontInstance(Element\Authentication $authentication = null)
    {
        if (!empty(self::$cloudFrontInstance)) {
            return self::$cloudFrontInstance;
        }

        self::$cloudFrontInstance = new CloudFrontClient(
            self::buildAuthentication($authentication)
        );

        return self::$cloudFrontInstance;
    }

    public static function buildAuthentication(Element\Authentication $authentication = null)
    {
        return [
            'version' => 'latest',
            'region'  => $authentication->getRegion(),
            'credentials' => [
                'key' => $authentication->getKey(),
                'secret' => $authentication->getSecret(),
            ]
        ];
    }
}