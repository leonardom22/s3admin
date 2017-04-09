<?php

namespace App\Response;

use App\Contract;

/**
 * Class Success
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Success extends Contract\Response
{
    private $content;

    /**
     * Success constructor.
     *
     * @param array $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @inheritDoc
     */
    public function getContent($converter)
    {
        return $converter->fromArray($this->content);
    }

    /**
     * @param mixed $content
     *
     * @return Success
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
}