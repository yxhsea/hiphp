<?php
/**
 * Project: hiphp
 * File: ErrorMessage.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/5/19
 * Time: 4:39 PM
 */

namespace App\Errors;

final class ErrorMessages
{
    const MESSAGE = [
        ErrorCodes::INTERNAL_ERROR => '网络异常, 请稍后重试',
    ];
}
