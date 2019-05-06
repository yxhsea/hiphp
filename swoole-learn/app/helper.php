<?php

// 定义一些工具函数

function config(string $key, string $default = '')
{
    $config = \App\Config\Config::getInstance();
    return $config->get($key, $default);
}
