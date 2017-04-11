<?php

namespace App\Action\Objects;

use App\Contract\Action;
use App\Response;
use App\Core;

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

        $path = Core\Storage::getPathDefault() . DS . 'tmp' . DS;

        if (!is_dir($path)) {
            mkdir($path);
        }

        $fileName = end(explode('/', $this->body['file']));

        $newFileName = round(microtime(true) * 1000) . '----' . $fileName;

        $pathFile = $path . $newFileName;

        try {
            $this->connection->getObject([
                'Bucket' => $this->body['bucket'],
                'Key' => $this->body['file'],
                'SaveAs' => $pathFile
            ]);

        } catch (\Exception $e) {

            return Response\Creator::error($e->getMessage());
        }

        chmod($pathFile, 0777);

        return new Response\Success([
            'file' => $newFileName
        ]);
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