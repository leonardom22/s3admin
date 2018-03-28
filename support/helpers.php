<?php

function printr()
{
    $args = func_get_args();
    foreach ($args as &$arg) {
        echo '<pre>';
        if (is_object($arg) || is_array($arg)) {
            print_r($arg);
        } elseif (empty($arg) || is_resource($arg)) {
            var_dump($arg);
        } else {
            echo (string)$arg;
        }
        echo '</pre>';
    }
}

function printrx()
{
    $args = func_get_args();
    call_user_func_array('printr', $args);
    die;
}

if (!function_exists('getallheaders')) {
    function getallheaders() {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}