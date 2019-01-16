<?php

namespace model;


use PDO;

class UserModel
{
    /**
     * @var PDO
     */

    private $newConnection;
    private $tableName = 'users';
    private $pageSize = 5;
    public $user_arr = array();


    public function __construct($db)
    {
        $this->newConnection = $db;
    }


    public function getUsers($userID = null)
    {

        $userID = intval($userID);

        if (empty($userID)) {

            $query = "SELECT id, first_name, last_name, user_name FROM " . $this->tableName;
            $getUsers = $this->newConnection->prepare($query);
            $getUsers->execute();
            $page = empty($_GET['page']) ? null : $_GET['page'];


            if ($row = $getUsers->rowCount() > 0) {

                if (empty($page)) {

                    $this->user_arr = $getUsers->fetchAll(PDO::FETCH_ASSOC);
                    echo json_encode($this->user_arr);
                    exit();

                } else {

                    $firstIdOnPage = ($page * $this->pageSize) - $this->pageSize;
                    $this->user_arr = $getUsers->fetchAll(PDO::FETCH_ASSOC);

                    if (!isset($this->user_arr[$firstIdOnPage])) {

                        $message = "Page is not find";
                        $this->user_arr["message"] = $message;
                        echo json_encode($this->user_arr);
                        header("No content", true, 204);
                        exit();

                    } else {

                        $first = $firstIdOnPage + 1;
                        $last = $page * $this->pageSize;
                        $count = count($this->user_arr);
                        $resultUserPage = array_slice($this->user_arr, $firstIdOnPage, $this->pageSize);
                        header("Partial Content", true, 206);
                        header("Content-range: {$first} - {$last}/ {$count}");
                        echo json_encode($resultUserPage);
                        exit();

                    }
                }

            } else {
                header('Bad request', true, 400);
            }

        } else {

            $query = "SELECT id, first_name, last_name, user_name FROM " . $this->tableName . " WHERE id = " . $userID;
            $getUsers = $this->newConnection->prepare($query);
            $getUsers->execute();

            if ($row = $getUsers->rowCount() > 0) {
                $this->user_arr = $getUsers->fetchAll(PDO::FETCH_ASSOC);
            } else {

                header('Bad request', true, 400);
                $user_item = array(
                    "message" => "The request was failed try again"
                );
                array_push($this->user_arr, $user_item);

            }

            echo json_encode($this->user_arr);
            exit();
        }

    }

    public function DeleteUser($userID)
    {

        if (empty($userID)) {

            $message = 'Please check your userId';
            header('Bad request', true, 400);
            $this->user_arr['message'] = $message;
            echo json_encode($this->user_arr);
            exit();

        } else {

            $query = "SELECT id FROM " . $this->tableName . " WHERE id = " . $userID;
            $getUsers = $this->newConnection->prepare($query);
            $getUsers->execute();
            $this->user_arr = $getUsers->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($this->user_arr)) {

                $query = "DELETE FROM " . $this->tableName . " WHERE id = " . $userID;
                $getUsers = $this->newConnection->prepare($query);
                $getUsers->execute();
                $count = $getUsers->rowCount();
                $message = 'We was deleted ' . $count . ' row';
                $this->user_arr = array();
                $this->user_arr['message'] = $message;
                header('User was deleted', true, 200);
                echo json_encode($this->user_arr);
                exit();

            } else {

                $message = 'Please, check your userId';
                $this->user_arr['message'] = $message;
                header('Bad request', true, 400);
                echo json_encode($this->user_arr);
                exit();

            }

        }

    }

    public function AuthorizationUser($authorizationData = null)
    {
        $username = $authorizationData['username'];
        $password = intval($authorizationData['password']);

        if (preg_match('/^[a-z\d_]{5,20}$/i', $username)) {

            $query = "SELECT * FROM users WHERE user_name = ? and password = ?";
            $getUsers = $this->newConnection->prepare($query);
            $getUsers->execute(array($username, $password));
            $row = $getUsers->rowCount();

            if ($row === 1) {
                header('Success', true, 200);
                $this->user_arr = array(
                    'authorization' => true,
                );
                echo json_encode($this->user_arr);
                exit();
            }

            header('Bad request', true, 400);
            $this->user_arr = array(
                'message' => 'Check your username or password'
            );
            echo json_encode($this->user_arr);
            exit();

        }
    }


}


