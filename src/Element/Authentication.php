<?php

namespace App\Element;

/**
 * Elemento respons�vel por modelar uma autentica��o da aplica��o.
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
     * Retorna o identificador da autentica��o.
     *
     * @return int
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Define o identificador da autentica��o.
     *
     * @param int $identifier
     *
     * @return Authentication
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * Retorna a chave.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Define a chava da autentica��o.
     *
     * @param string $key
     *
     * @return Authentication
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Retorna a chave secreta da autentica��o.
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Define a chave secreta.
     *
     * @param string $secret
     *
     * @return Authentication
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * Retorna a regi�o.
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Define a regi�o.
     *
     * @param string $region
     *
     * @return Authentication
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }
}
