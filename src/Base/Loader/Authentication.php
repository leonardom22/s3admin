<?php

namespace App\Base\Loader;

use App\Converter\Json;
use App\Core;
use App\Element;

/**
 * Classe responsável por carregar as autenticações cadastradas.
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Authentication
{
    /**
     * Retorna uma autenticação cadastrada.
     *
     * @param string $identifier
     *
     * @return Element\Authentication|null
     */
    public function getAuthentication($identifier)
    {
        $authentications = Core\Storage::retrieve('authentications.json', new Json);

        foreach ($authentications as $value) {
            if ($value['identifier'] == $identifier) {
                return $this->buildAuthenticationElement($value);
            }
        }

        return null;
    }

    /**
     * Converte as autenticações em elementos.
     *
     * @param array $authentication
     *
     * @return Element\Authentication
     */
    private function buildAuthenticationElement(array $authentication)
    {
        return (new Element\Authentication)
            ->setIdentifier($authentication['identifier'])
            ->setKey($authentication['key'])
            ->setRegion($authentication['region'])
            ->setSecret($authentication['secret']);
    }
}