<?php

namespace controller;

require __DIR__  . '\..\configs\DataBaseConnection.php';
require __DIR__ . '\..\models\UserModel.php';


class UserController {


    private $db;
    private $resourcesId;
    private $authorizationData;


    function __construct($resourcesId = null, $authorizationData = null)
    {
            $this->resourcesId = $resourcesId;
            $this->authorizationData = $authorizationData;
            $connection =  new \configs\DataBaseConnection();
            $this->db = $connection -> connection();
    }

    public function Get(){
        $user = new \model\UserModel($this->db);
        $user->getUsers($this->resourcesId);
    }


    public function Delete(){
        $user = new \model\UserModel($this->db);
        $user->DeleteUser($this->resourcesId);
    }

    public function UserAuthorization(){
        $user = new \model\UserModel($this->db);
        $user->AuthorizationUser($this->authorizationData);
    }


}