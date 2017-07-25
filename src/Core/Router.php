<?php

namespace App\Core;

use App\Exception;

/**
 * Classe responsável pelas rotas da aplicação.
 *
 * @author Leonardo Oliveira <leonardo.malia@live.com>
 */
class Router
{
    /** @var array */
    private $parameters;

    /**
     * Retorna a classe de acordo com a rota.
     *
     * @throws Exception\ResourceNotFound
     * @throws Exception\MethodNotAllowed
     *
     * @return string
     */
    public function getClassByRoute()
    {
        $uri = $this->getUri();

        if ($uri == false) {
            throw new Exception\ResourceNotFound();
        }

        $routes = Config::get('routes');

        $departments = explode('/', $uri);

        $route = [];

        foreach ($routes as $routePath => $methods) {
            if ($this->compareRoute($departments, $routePath)) {
                $route = $routes[$routePath];
                break;
            }
        }

        $method = Request::getMethod()->value();

        if (empty($route)) {
            throw new Exception\ResourceNotFound();
        }
        
        if (empty($route[$method])) {
            throw new Exception\MethodNotAllowed();
        }

        return $route[$method]['action'];
    }

    /**
     * Retorna parametros.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Retorna url.
     * 
     * @return bool|string
     */
    private function getUri()
    {
        if (!empty($_GET['_url'])) {
            return $_GET['_url'];
        }
        
        return false;
    }

    /**
     * Compara rotas.
     *
     * @param $departments
     * @param $route
     *
     * @return bool
     */
    private function compareRoute($departments, $route)
    {
        $routes = explode('/', trim($route, '/'));

        $routeFound = true;
        $parameters = [];

        foreach ($routes as $key => $route) {

            if (!isset($departments[$key])) {
                $routeFound = false;
                break;
            }

            if (strpos($route, '{') !== false) {
                $parameters[str_replace(['{', '}'], '', $route)] = $departments[$key];
                unset($departments[$key]);
                continue;
            }

            if ($route == $departments[$key]) {
                unset($departments[$key]);
                continue;
            }

            $routeFound = false;
            break;
        }

        if (!empty(array_filter($departments))) {
            return false;
        }

        if ($routeFound == true) {
            $this->parameters = $parameters;
        }

        return $routeFound;
    }
}