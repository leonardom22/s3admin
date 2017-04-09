<?php

namespace App\Action\Authentications;

use App\Contract;
use App\Converter\Json;
use App\Core;
use App\Enum\HttpStatusCode;
use App\Response;

/**
 * Class Register
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Register extends Contract\Action
{
    /**
     * @inheritDoc
     */
    public function execute(array $parameters = [])
    {
        $authentications = Json::toArray(Core\Storage::retrieve('authentications.json'));

        if (empty($authentications)) {
            $authentications = [];
        }

        $identifier = $this->body['identifier'];
        if (empty($identifier)) {
            $identifier = count($authentications) + 1;
        }

        $index = $this->authenticationExists($authentications, $identifier);

        $save = [
            'name' => $this->body['name'],
            'identifier' => $identifier,
            'key' => $this->body['key'],
            'secret' => $this->body['secret'],
            'region' => $this->body['region']
        ];

        if ($index == false) {
            $authentications[] = $save;
        } else {
            $authentications[$index] = $save;
        }

        $saved = Core\Storage::save('authentications.json', Json::fromArray($authentications));

        if ($saved == false) {
            $error = (new Response\Error('Unable to save'))
                ->addCause('File or folder permission')
                ->setHttpStatusCode(HttpStatusCode::INTERNAL_SERVER_ERROR());

            return $error;
        }

        return new Response\NoResponse();
    }

    /**
     * @inheritDoc
     */
    public function requiredFields()
    {
        return [
            'name',
            'key',
            'secret',
            'region'
        ];
    }

    private function authenticationExists($authentications, $identifier)
    {
        foreach ($authentications as $index => $authentication) {
            if ($authentication['identifier'] == $identifier) {
                return $index;
            }
        }

        return false;
    }
}