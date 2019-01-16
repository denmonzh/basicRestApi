<?php

namespace configs;

use PDO;
use PDOException;

class DataBaseConnection{

    private $host = '127.0.0.1:3306';
    private $dbname = 'users_db';
    private $user = 'root';
    private $password = '';
    public $conn;


    public function connection(){

        try{
            $this->conn = new PDO("mysql:host=". $this->host.";dbname=" .$this->dbname, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        }catch (PDOException $e){

            echo ('Connection was failed ' .$e);
        }

        return $this->conn;
    }


}