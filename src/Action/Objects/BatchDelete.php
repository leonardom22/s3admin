<?php

namespace App\Action\Objects;

use App\Contract\Action;
use App\Response;

/**
 * Recurso responsável por efetuar exclusão massiva de arquivos.
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class BatchDelete extends Action\ConnectionRequired
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

            $this->deleteObjects();

        } catch (\Exception $e) {

            printrx($e->getMessage());

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
            'files'
        ];
    }

    /**
     * Deleta uma coleção de objetos no S3.
     */
    private function deleteObjects()
    {
        $files = [];
        foreach ($this->body['files'] as $file) {
            $files[] = [
                'Key' => trim($file)
            ];
        }

        $this->connection->deleteObjects([
            'Bucket' => $this->body['bucket'],
            'Delete' => [
                'Objects' => $files
            ]
        ]);
    }
}