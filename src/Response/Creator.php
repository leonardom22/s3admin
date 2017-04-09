<?php

namespace App\Response;

use App\Enum;

/**
 * Class Creator
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Creator
{
    /**
     * Recurso não encontrado.
     * 
     * @param null|string $message
     *
     * @return \App\Contract\Response
     */
    public static function resourceNotFound($message = null)
    {
        return (new Error($message ?: 'Recurso não encontrado.'))
            ->setHttpStatusCode(Enum\HttpStatusCode::NOT_FOUND());
    }

    /**
     * Método não permitido.
     * 
     * @param null|string $message
     *
     * @return \App\Contract\Response
     */
    public static function methodNotAllowed($message = null)
    {
        return (new Error($message ?: 'Método não permitido.'))
            ->setHttpStatusCode(Enum\HttpStatusCode::METHOD_NOT_ALLOWED());
    }

    /**
     * @return \App\Contract\Response
     */
    public static function notImplemented()
    {
        return (new Error('Recurso não implementado.'))
            ->setHttpStatusCode(Enum\HttpStatusCode::NOT_IMPLEMENTED());
    }

    /**
     * @return \App\Contract\Response
     */
    public static function notAuthorized()
    {
        return (new Error('Não autorizado.'))
            ->addCause('Header de autenticação não existe.')
            ->addCause('Autenticação inválida ou expirada.')
            ->addCause('Falta de pagamento.')
            ->setHttpStatusCode(Enum\HttpStatusCode::NOT_AUTHORIZED());
    }

    /**
     * @param null|string $message
     *
     * @return \App\Contract\Response
     */
    public static function invalidRequest($message = null)
    {
        return (new Error($message ?: 'Requisição inválida'))
            ->setHttpStatusCode(Enum\HttpStatusCode::INVALID_REQUEST());
    }
    
    /**
     * @param null|string $message
     *
     * @return \App\Contract\Response
     */
    public static function invalidSku($message = null)
    {
        return (new Error($message ?: 'Sku inválido'))
            ->setHttpStatusCode(Enum\HttpStatusCode::INVALID_REQUEST());
    }

    /**
     * @param null|string $message
     *
     * @return \App\Contract\Response
     */
    public static function prohibited($message = null)
    {
        return (new Error($message ?: 'Proibido'))
            ->setHttpStatusCode(Enum\HttpStatusCode::PROHIBITED());
    }

    /**
     * @param null $message
     *
     * @return \App\Contract\Response
     */
    public static function error($message = null)
    {
        return (new Error($message ?: 'error'))
            ->setHttpStatusCode(Enum\HttpStatusCode::INTERNAL_SERVER_ERROR());
    }
}