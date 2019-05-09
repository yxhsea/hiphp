<?php
/**
 * Project: hiphp
 * File: UserController.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/8/19
 * Time: 10:41 AM
 */

namespace App\Http\Controllers;

use Swoole\Http\Request;

class UserController extends BaseController
{
    public function getInfo(Request $request)
    {
        $name = $request->get["name"];
        return $this->success(["username" => $name, "age"=>23]);
    }
}
