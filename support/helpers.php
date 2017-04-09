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