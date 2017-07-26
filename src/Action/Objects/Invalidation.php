<?php

namespace App\Action\Objects;

use App\Aws;
use App\Contract;
use App\Base\Loader;
use App\Response;

/**
 * Recurso responsável por invalidação de arquivos no cloudfront.
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Invalidation extends Contract\Action
{
    /**
     * @inheritDoc
     */
    public function execute(array $parameters = [])
    {
        $objects = array_filter($this->body['objects']);

        if (empty($objects)) {
            return Response\Creator::invalidRequest('Invalid Objects');
        }

        $connection = $this->getConnection($parameters['connectionId']);

        if (!$connection) {
            return Response\Creator::invalidRequest('Invalid Connection');
        }

        try {

            $distributions = $connection->listDistributions();

        } catch (\Exception $e) {

            return Response\Creator::error($e->getMessage());
        }

        $distributionList = $distributions->get('DistributionList');

        foreach ($distributionList['Items'] as $distribution) {

            try {
                $connection->createInvalidation([
                    'DistributionId' => $distribution['Id'],
                    'InvalidationBatch' => [
                        'Paths' => [
                            'Quantity' => count($objects),
                            'Items' => $objects
                        ],
                        'CallerReference' => time()
                    ]
                ]);
            } catch (\Exception $e) {
                // previne fatal error
            }
        }

        return new Response\NoResponse();
    }

    /**
     * @inheritDoc
     */
    public function requiredFields()
    {
        return [
            'objects'
        ];
    }

    /**
     * Retorna facade do cloudfront.
     *
     * @param $connectionId
     *
     * @return \Aws\CloudFront\CloudFrontClient|bool
     */
    private function getConnection($connectionId)
    {
        $loader = new Loader\Authentication();

        $authentication = $loader->getAuthentication($connectionId);

        if (empty($authentication)) {
            return false;
        }

        return Aws\Sdk::getCloudFrontInstance($authentication);
    }
}
