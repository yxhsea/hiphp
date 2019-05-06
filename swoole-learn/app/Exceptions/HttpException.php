<?php
/**
 * Project: hiphp
 * File: HttpException.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/5/19
 * Time: 4:24 PM
 */

namespace App\Exceptions;

use Throwable;

class HttpException extends BaseException
{
    const HTTP_BAD_REQUEST = 400;
    private $headers;
    private $statusCode;

    public function __construct(
        $message = "",
        $code = 0,
        Throwable $previous = null,
        $statusCode = self::HTTP_BAD_REQUEST,
        $headers = array()
    ) {
        parent::__construct($message, $code, $previous);

        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}
