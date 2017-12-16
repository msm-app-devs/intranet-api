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
                               array("GET"=>"getNews", "POST" => "addnews", "PUT" => "updatenews", "DELETE"=>"deletenews", "OPTIONS" => "option"),
                                "admin"  =>
                                array("POST"=>"token")
                                ];

    private $theController;

    private $theMethod;

    private $requestMethod;

    private $emberController;

    private $phpInput = array();

    public function __construct($controller, $method)
    {
        $this->theController = $controller;
        $this->requestMethod = $method;
        $this->emberController = $controller;
//        $this->tokenCheck();
        if ($this->theController == "imageupdate") {
                $this->setMethod("updateemployeeimage");
        } else {
                $this->setMethod(self::$methods[$this->theController][$this->requestMethod]);
        }


        if ($this->theController == "employees") {
            $this->emberController = "employee";
        }



//        else if (count($_POST) > 0) {
//        if ($this->theMethod != "token") {
            ////////////////////////////////// FOR THE IMAGE UPDATE (IN TESTING PHASE) ////////////////////////////////////
            if ($this->theController == "imageupdate") {
                if (array_key_exists("image", $_FILES)) {

                    $this->theController = "employees";
                    $_POST["imageprop"] = $_FILES["image"];

                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
            } else if ($this->requestMethod === "POST") {

                if (array_key_exists($this->emberController ,$_POST)) {
                    $_POST = $_POST[$this->emberController];
                }
                if (array_key_exists($this->emberController, $_FILES)) {

                    $_POST["imageprop"] = $_FILES[$this->emberController];

                }

            } else if ($this->requestMethod === "PUT") {

                $this->phpInput = json_decode(file_get_contents("php://input"), true);
                $_POST = $this->phpInput[$this->emberController];
            }
//        }

    }

//    private function tokenCheck() {
//        if ($this->theController == "token") {
//            $this->theController = "admin";
//            $this->theMethod = "token";
//        } else {
//            $this->theMethod = self::$methods[$this->theController][$this->requestMethod];
//        }
//    }
    public function setController($controller) {

        $this->theController = $controller;
    }

    public function getController() {

        return $this->theController;

    }

    public function setMethod($method) {
        $this->theMethod = $method;
    }

    public function getMethod() {

            return $this->theMethod;

    }
}