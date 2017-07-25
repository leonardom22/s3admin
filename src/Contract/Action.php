<?php

namespace App\Contract;

use App\Enum;
use App\Core;
use App\Response;
use App\Contract;

/**
 * Classe abstrata que define as actions.
 * 
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
abstract class Action
{
    /** @var array */
    public $body;

    /**
     * Função chamada antes da execução da ação.
     *
     * @return Contract\Response|bool
     */
    public function startup()
    {
        switch (Core\Request::getMethod()) {
            case Enum\HttpMethod::POST():
            case Enum\HttpMethod::PUT():

                $this->body = Core\Request::getBody();

                $requiredFields = $this->requiredFields();

                if ($requiredFields != false) {
                    $validated = Core\Request::validate($requiredFields, $this->body);

                    if ($validated != false) {
                        return Response\Creator::invalidRequest('Field not found: \'' . $validated . '\'');
                    }
                }

                break;
            default:
        }

        return false;
    }

    /**
     * Executa ação.
     * 
     * @param array $parameters
     * 
     * @return mixed
     */
    public abstract function execute(array $parameters = []);

    /**
     * Campos obrigatórios.
     *
     * @return array
     */
    public abstract function requiredFields();
}