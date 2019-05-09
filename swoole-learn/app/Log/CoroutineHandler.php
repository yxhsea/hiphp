<?php
/**
 * Project: hiphp
 * File: CoroutineHandler.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/9/19
 * Time: 5:17 PM
 */

use \Monolog\Handler\AbstractProcessingHandler;

class CoroutineHandler extends AbstractProcessingHandler
{
    public function __construct($level = \Monolog\Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(array $record)
    {
        // TODO: Implement write() method.
    }
}
