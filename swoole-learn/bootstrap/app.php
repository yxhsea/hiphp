<?php
/**
 * Project: hiphp
 * File: app.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 4/28/19
 * Time: 12:02 PM
 */

if (!file_exists($file = __DIR__ . '/../vendor/autoload.php')) {
    throw new RuntimeException("Load dependencies failed to run this script.");
}

include $file;
define("ROOT_PATH", realpath(__DIR__ . '/../'));
