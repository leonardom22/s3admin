<?php

namespace App\Enum;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class HttpStatusCode
 * 
 * @method static HttpStatusCode OK()
 * @method static HttpStatusCode NOT_FOUND()
 * @method static HttpStatusCode METHOD_NOT_ALLOWED()
 * @method static HttpStatusCode NOT_AUTHORIZED()
 * @method static HttpStatusCode PROHIBITED()
 * @method static HttpStatusCode NOT_IMPLEMENTED()
 * @method static HttpStatusCode INTERNAL_SERVER_ERROR()
 * @method static HttpStatusCode INVALID_REQUEST()
 * 
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
final class HttpStatusCode extends AbstractEnumeration
{
    /** @var integer Ok */
    const OK = 200;

    /** @var integer Requisição inválida */
    const INVALID_REQUEST = 400;

    /** @var integer Não autorizado */
    const NOT_AUTHORIZED = 401;

    /** @var integer Proibido */
    const PROHIBITED = 403;

    /** @var integer Não encontrado */
    const NOT_FOUND = 404;

    /** @var integer Método não permitido */
    const METHOD_NOT_ALLOWED = 405;

    /** @var integer Erro interno */
    const INTERNAL_SERVER_ERROR = 500;

    /** @var integer Não implementado */
    const NOT_IMPLEMENTED = 501;
}