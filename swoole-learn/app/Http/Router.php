<?php
/**
 * Project: hiphp
 * File: Router.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/7/19
 * Time: 2:33 PM
 */

namespace App\Http;


final class Router
{
    public static $routerList = array();

    protected function addRoute($methods, $uri, $action)
    {
        $array = array($methods . $uri => $action);
        self::$routerList = array_merge(self::$routerList, $array);
    }

    public static function get($uri, $action = null)
    {
        (new self())->addRoute('GET', $uri, $action);
    }

    public static function post($uri, $action = null)
    {
        (new self())->addRoute('POST', $uri, $action);
    }

    public static function put($uri, $action = null)
    {
        (new self())->addRoute('PUT', $uri, $action);
    }

    public static function delete($uri, $action = null)
    {
        (new self())->addRoute('DELETE', $uri, $action);
    }
}
