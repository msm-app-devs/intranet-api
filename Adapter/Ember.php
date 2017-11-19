<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 14.10.2017 г.
 * Time: 0:07
 */

namespace Employees\Adapter;


class Ember
{
    private static $methods = ["GET"=>"list", "POST"=>"addemployee","PUT"=>"updateemployee", "DELETE"=>"removeemployee", "OPTIONS" => "option"];

    private $theController;

    private $theMethod;

    private $phpInput = array();

    public function __construct($controller, $method)
    {
        $this->theController = $controller;
        $this->theMethod = $method;

        if ($this->theController == "token") {
            $this->theController = "admin";
            $this->theMethod = "token";
        } else {
//            parse_str(file_get_contents("php://input"), $this->phpInput);
            if ($this->theMethod === "PUT" || $this->theMethod === "POST") {
                $this->phpInput = json_decode(file_get_contents("php://input"), true);

                $_POST = $this->phpInput['employee'];
            }
        }



    }

    private function customCheck() {
        if ($this->theController == "token") {
            $this->theController = "admin";
            $this->theMethod = "token";
        }
    }

    public function getController() {
        //employees/token
        return $this->theController;

    }

    public function getMethod() {

        if ($this->theMethod == "token") {
            return "token";
        } else {
            return self::$methods[$this->theMethod];
        }

    }
}