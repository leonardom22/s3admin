<?php

namespace App\Action\Objects;

use App\Contract\Action;
use App\Response;
use App\Core;

/**
 * Recurso responsável criar novos arquivos.
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Create extends Action\ConnectionRequired
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

        $path = Core\Storage::createFolder('tmp');

        $errors = [];
        foreach ($this->body['files'] as $file) {
            $fileName = end(explode('----', $file));

            $key = $fileName;
            if (!empty($this->body['path']) && $this->body['path'] != '/') {
                $key = rtrim($this->body['path'], '/') . '/' . $fileName;
            }

            try {
                $this->sendObject($key, $path . trim($file, '/'));

                unlink($path . trim($file, '/'));
            } catch (\Exception $e) {
                $errors[] = [
                    'file' => $fileName,
                    'message' => $e->getMessage()
                ];
            }
        }

        if (empty($errors)) {
            return new Response\NoResponse();
        }

        return new Response\Success([
            'unsent_files' => $errors
        ]);
    }

    /**
     * @inheritDoc
     */
    public function requiredFields()
    {
        return [
            'path',
            'files',
            'bucket'
        ];
    }

    /**
     * Efetua requisição de envio de arquivo para o S3.
     *
     * @param string $key
     * @param string $sourceFile
     *
     * @return bool
     */
    private function sendObject($key, $sourceFile)
    {
        $this->connection->putObject([
            'Bucket' => $this->body['bucket'],
            'Key' => $key,
            'SourceFile' => $sourceFile
        ]);

        return true;
    }
}