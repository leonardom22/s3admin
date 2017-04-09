<?php

namespace App\Action\Authentications;

use App\Contract;
use App\Converter\Json;
use App\Core;
use App\Response;

/**
 * Class Retrieve
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Retrieve extends Contract\Action
{
    /**
     * @inheritdoc
     */
    public function execute(array $parameters = [])
    {
        $authentications = Json::toArray(Core\Storage::retrieve('authentications.json'));

        if (empty($authentications)) {
            $authentications = [];
        }

        return new Response\Success($authentications);
    }

    /**
     * @inheritDoc
     */
    public function requiredFields()
    {
        return false;
    }
}