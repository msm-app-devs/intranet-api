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
    private $methods = ["GET"=>"list", "POST"=>"addemployee","PUT"=>"updateemployee", "DELETE"=>"removeemployee"];

    private $theMethod;

    private $phpInput = array();

    public function __construct($method)
    {
        $this->theMethod = $method;
        if ($method === "PUT") {
            parse_str(file_get_contents("php://input"), $this->phpInput);
            $_POST = $this->phpInput;
        }
    }

    public function getMethod() {

        return $this->methods[$this->theMethod];
    }
}