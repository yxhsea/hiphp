<?php
/**
 * Project: hiphp
 * File: BaseController.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/8/19
 * Time: 3:49 PM
 */
namespace App\Http\Controllers;

abstract class BaseController
{
    public function success(array $data = []): string
    {
        return \GuzzleHttp\json_encode(
            [
                "code"    => 0,
                "data"    => $data,
                "message" => "success"
            ]
        );
    }

    public function fail(int $code, string $message): string
    {
        return \GuzzleHttp\json_encode(
            [
                "code"    => $code,
                "message" => $message
            ]
        );
    }
}
