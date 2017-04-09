<?php

namespace App\Core;

use App\Exception;
use App\Contract;
use App\Response;
use App\Core;

/**
 * Class Application
 * 
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Application
{
    /**
     * Application constructor.
     * 
     * @param string $appDir
     */
    public function __construct($appDir)
    {
        Core\Config::setAppDir($appDir);
    }

    /**
     * @return Core\Response
     */
    public function start()
    {
        $router = new Router();

        $route = null;

        try {
            $route = $router->getClassByRoute();

        } catch (Exception\MethodNotAllowed $e) {

            $error = Response\Creator::methodNotAllowed($e->getMessage());
            
        } catch (\Exception $e) {

            $error = Response\Creator::resourceNotFound($e->getMessage());

        } finally {

            if (!empty($error)) {
                return new Core\Response($error);
            }
        }

        return $this->callAction($route, $router->getParameters());
    }

    /**
     * @param string $route
     * @param array $parameters
     *
     * @return Response\NoResponse|Core\Response
     */
    private function callAction($route, array $parameters = [])
    {
        $class = 'App\Action\\' . str_replace('/', '\\', $route);

        if (class_exists($class) == false) {
            return new Core\Response(Response\Creator::notImplemented());
        }

        $action = new $class;

        if (!$action instanceof Contract\Action) {
            return new Core\Response(Response\Creator::notImplemented());
        }

        $started = $action->startup();

        if ($started != false) {
            return new Core\Response($started);
        }

        $response = $action->execute($parameters);

        if ($response instanceof Contract\Response) {
            return new Core\Response($response);
        }

        return new Response\NoResponse;
    }
}