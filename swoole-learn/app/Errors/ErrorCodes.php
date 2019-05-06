<?php
/**
 * Project: hiphp
 * File: Errors.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/5/19
 * Time: 4:38 PM
 */

namespace App\Errors;

final class ErrorCodes
{
    //错误码定义
    /**
     * 错误码共5位，按如下方式分类
     *  0 00 00
     *  第一位标示错误级别
     *  第二三位标示模块
     *  第四五六七位标示具体错误码
     */

    const INTERNAL_ERROR = 10001;
}
