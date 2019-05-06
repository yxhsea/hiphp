<?php
/**
 * Project: hiphp
 * File: BaseException.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/5/19
 * Time: 4:17 PM
 */

namespace App\Exceptions;

use Throwable;

abstract class BaseException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
