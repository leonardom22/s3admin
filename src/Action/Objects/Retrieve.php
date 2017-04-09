<?php

namespace App\Action\Objects;

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
            $object = $this->connection->getObject([
                'Bucket' => $this->body['bucket'],
                'Key' => $this->body['file']
            ]);

        } catch (\Exception $e) {

            return Response\Creator::error($e->getMessage());
        }

        printrx($object);
    }

    /**
     * @inheritDoc
     */
    public function requiredFields()
    {
        return [
            'file',
            'bucket'
        ];
    }
}