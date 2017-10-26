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
    private $methods = ["GET"=>"list", "POST"=>"addemployee","PUT"=>"updateemployee", "DELETE"=>"removeemployee", "OPTIONS" => "option"];

    private $theMethod;

    private $phpInput = array();

    public function __construct($method)
    {
            $this->theMethod = $method;
//            parse_str(file_get_contents("php://input"), $this->phpInput);
            if ($this->theMethod === "PUT" || $this->theMethod === "POST") {
                $this->phpInput = json_decode(file_get_contents("php://input"),true);
                $_POST = $this->phpInput['employee'];
            }

    }

    public function getMethod() {

        return $this->methods[$this->theMethod];

    }
}