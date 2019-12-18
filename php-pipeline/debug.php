<?php
/**
 * Project: hiphp
 * File: debug.php
 *
 * Created by PhpStorm.
 * User: yxhsea
 * Email: xionghaiyang@hk01.com
 * Date: 12/18/19
 * Time: 5:33 PM
 */

function Pipeline($stack, $pipe)
{
    return function ($passable) use ($stack, $pipe) {
        if (is_callable($pipe)) {
            $pipe($passable, $stack);
        } elseif (is_object($pipe)) {
            $method = 'handle';
            if (!method_exists($pipe, $method)) {
                throw new InvalidArgumentException('object that own handle method');
            } else {
                $pipe->$method($passable, $stack);
            }
        } else {
            throw new InvalidArgumentException('$pipe must be callback or object');
        }
    };
}

interface TestUnit
{
    public function handle($passable, callable $next = null);
}

class Unit1 implements TestUnit
{
    public function handle($passable, callable $next = null)
    {
        echo __CLASS__ . '->' . __METHOD__ . " called\n";
        $next($passable);
    }
}

class Unit2 implements TestUnit
{
    public function handle($passable, callable $next = null)
    {
        echo __CLASS__ . '->' . __METHOD__ . " called\n";
        $next($passable);
    }
}

class Unit3 implements TestUnit
{
    public function handle($passable, callable $next = null)
    {
        echo __CLASS__ . '->' . __METHOD__ . " called\n";
        $next($passable);
    }
}

class InitialValue implements TestUnit
{
    public function handle($passable, callable $next = null)
    {
        echo __CLASS__ . '->' . __METHOD__ . "called\n";
        $next($passable);
    }
}

$pipeline = array_reduce([new Unit1(), new Unit2(), new Unit3()], 'Pipeline', function($passable) {
    (new InitialValue())->handle($passable);
});

$pipeline(1);
