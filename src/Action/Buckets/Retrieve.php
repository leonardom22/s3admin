<?php

namespace App\Action\Buckets;

use App\Contract\Action;
use App\Response;

/**
 * Class Retrieve
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Retrieve extends Action\ConnectionRequired
{
    /**
     * @inheritDoc
     */
    public function execute(array $parameters = [])
    {
        $validated = $this->validConnection($parameters);

        if ($validated !== true) {
            return $validated;
        }

        try {

            $response = $this->connection->listBuckets();
        } catch (\Exception $e) {

            return Response\Creator::error($e->getMessage());
        }

        $buckets = [];

        foreach ($response['Buckets'] as $bucket) {
            $buckets[] = [
                'name' => $bucket['Name'],
                'date' => $bucket['CreationDate']->format('Y-m-d H:i:s')
            ];
        }

        return new Response\Success($buckets);
    }

    /**
     * @inheritDoc
     */
    public function requiredFields()
    {
        return false;
    }
}