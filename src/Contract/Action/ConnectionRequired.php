<?php

namespace App\Contract\Action;

use App\Contract;
use App\Response;
use App\Base\Loader;
use App\Aws\Sdk;

/**
 * Class ConnectionRequired
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
abstract class ConnectionRequired extends Contract\Action
{
    /** @var \Aws\S3\S3Client */
    protected $connection;

    /**
     * @param array $parameters
     *
     * @return Contract\Response|bool
     */
    public function validConnection(array $parameters = [])
    {
        if (empty($parameters['connectionId'])) {
            return Response\Creator::invalidRequest('Invalid Connection');
        }

        $this->connection = $this->getS3Client($parameters['connectionId']);

        if ($this->connection == false) {
            return Response\Creator::invalidRequest('Invalid Connection');
        }

        return true;
    }

    /**
     * @param $connectionId
     *
     * @return \Aws\S3\S3Client|bool
     */
    protected function getS3Client($connectionId)
    {
        $loader = new Loader\Authentication();

        $authentication = $loader->getAuthentication($connectionId);

        if (empty($authentication)) {
            return false;
        }

        return Sdk::getS3Instance($authentication);
    }
}