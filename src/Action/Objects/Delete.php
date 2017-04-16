<?php

namespace App\Action\Objects;

use App\Contract\Action;
use App\Response;

/**
 * Class Delete
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Delete extends Action\ConnectionRequired
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

            $this->connection->deleteObject([
                'Bucket' => $this->body['bucket'],
                'Key' => $this->body['file']
            ]);

        } catch (\Exception $e) {

            return Response\Creator::error($e->getMessage());
        }

        return new Response\NoResponse();
    }

    /**
     * @inheritDoc
     */
    public function requiredFields()
    {
        return [
            'bucket',
            'file'
        ];
    }
}