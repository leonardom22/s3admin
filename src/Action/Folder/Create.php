<?php
namespace App\Action\Folder;

use App\Contract\Action;
use App\Response;

/**
 * Recurso responsável por criar uma pasta.
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Create extends Action\ConnectionRequired
{
    /**
     * @inheritdoc
     */
    public function execute(array $parameters = [])
    {
        $validated = $this->validConnection($parameters);

        if ($validated !== true) {
            return $validated;
        }

        try {

            $this->connection->putObject([
                'Bucket' => $this->body['bucket'],
                'Key' => trim($this->body['folder'], '/') . '/',
                'Body' => ''
            ]);

        } catch (\Exception $e) {

            return Response\Creator::error($e->getMessage());
        }

        return new Response\NoResponse();
    }

    /**
     * @inheritdoc
     */
    public function requiredFields()
    {
        return [
            'folder',
            'bucket'
        ];
    }
}