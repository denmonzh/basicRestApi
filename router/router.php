<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 28.12.2018
 * Time: 11:57
 * @param $method
 * @param $resources
 */

require __DIR__ . '\..\controller\UserController.php';


function router($method, $resources, $resourceId = null, $authorizationData = null)

{

    if ($method === 'get' && $resources === 'user') {
        $user = new controller\UserController($resourceId);
        $user->Get();
    } elseif ($method === 'delete' && $resources === 'user') {
        $user = new \controller\UserController($resourceId);
        $user->Delete();
    } elseif ($method === 'post' && $resources === 'login') {
        $user = new \controller\UserController($resourceId, $authorizationData);
        $user->UserAuthorization();
    }

}