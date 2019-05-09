<?php
/**
 * Project: hiphp
 * File: api.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/6/19
 * Time: 10:32 AM
 */

use \App\Http\Router;

Router::get('/user/get', 'UserController@getInfo');
Router::post('/user/add', 'UserController@add');
Router::put('/user/update', 'UserController@update');
Router::delete('/user/delete', 'UserController@delete');
