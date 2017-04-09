<?php

namespace App\Response;

use App\Contract;

/**
 * Class NoResponse
 * 
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class NoResponse extends Contract\Response
{
    /**
     * @inheritDoc
     */
    public function getContent($converter)
    {
        return '';
    }
}