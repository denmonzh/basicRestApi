<?php

require './router/router.php';


$parts = parse_url($_SERVER['REQUEST_URI']);
$path = trim($parts['path'], '/');

$parts = explode('/', $path);
$parts = array_filter($parts);

//Check if request resources parameters is available

if(empty($parts)){
    header('Bad request', true, 400);
    exit();
}

$resources = $parts['0'];
$resourcesId = !empty($parts['1']) ? $parts['1'] : null;

$method = strtolower($_SERVER['REQUEST_METHOD']);

if(empty($resourcesId) && in_array($method, ['delete', 'put', 'patch'])){
    $message = 'This method should have id';
    $messageArr = array(
        'message' => $message
    );
    echo json_encode($messageArr);
    header('Content-Type: application/json');
    header('Bad Request', true , 404);
    exit();
}


if($method === 'post' && $resources === 'login'){
    $json = file_get_contents('php://input');
    $authorizationData = json_decode($json, true);
}

header('Content-Type: application/json');


router($method, $resources, $resourcesId, $authorizationData);

