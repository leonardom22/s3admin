<?php

namespace App\Element;

/**
 * Elemento responsável por modelar uma autenticação da aplicação.
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
     * Retorna o identificador da autenticação.
     *
     * @return int
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Define o identificador da autenticação.
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
     * Define a chava da autenticação.
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
     * Retorna a chave secreta da autenticação.
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
     * Retorna a região.
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Define a região.
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
