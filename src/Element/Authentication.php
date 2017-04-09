<?php

namespace App\Element;

/**
 * Class Authentication
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Authentication
{
    /** @var integer Identificador */
    private $identifier;

    /** @var string */
    private $key;

    /** @var string */
    private $secret;

    /** @var string */
    private $region;

    /**
     * @return int
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param int $identifier
     * @return Authentication
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return Authentication
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     * @return Authentication
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @return Authentication
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }
}