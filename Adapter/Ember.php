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
    private static $methods = ["employees" =>
                                array("GET"=>"list", "POST"=>"addemployee","PUT"=>"updateemployee", "DELETE"=>"removeemployee", "OPTIONS" => "option"),
                               "news" =>
                               array("GET"=>"getNews", "POST" => "addnews", "PUT" => "updatenews", "DELETE"=>"deletenews", "OPTIONS" => "option")
                                ];

    private $theController;

    private $theMethod;

    private $phpInput = array();

    public function __construct($controller, $method)
    {
        $this->theController = $controller;
        $this->theMethod = $method;

        if ($this->theController == "admin") {
            $this->theController = "admin";
            $this->theMethod = "token";
        }
        else if (count($_POST) > 0) {

        } else {
//            parse_str(file_get_contents("php://input"), $this->phpInput);
            if ($this->theMethod === "PUT" || $this->theMethod === "POST") {
                        $this->phpInput = json_decode(file_get_contents("php://input"), true);

//                $_POST = $this->phpInput['employee'];
                if ($this->theController == "employees") {
                    $_POST = $this->phpInput["employee"];
                } else {
                    $_POST = $this->phpInput[$this->theController];
                }


            }
        }

    }

    private function customCheck() {
        if ($this->theController == "admin") {
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
            return self::$methods[$this->theController][$this->theMethod];
        }

    }
}