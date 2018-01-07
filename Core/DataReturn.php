<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 29.8.2017 г.
 * Time: 12:20
 */

namespace Employees\Core;


use Employees\Config\DefaultParam;
use Employees\Core\MVC\MVCContextInterface;
use function PHPSTORM_META\type;

class DataReturn implements DataReturnInterface
{
    private $mvcContext;

    private $dataContainer;

    public function __construct(MVCContextInterface $MVCContext)
    {
        $this->mvcContext = $MVCContext;
    }

    public function jsonData($theData)
    {
        print_r(json_encode(array($this->mvcContext->getController() => $theData)));
    }

    public function errorMessage($message)
    {
        print_r($message);
//        http_response_code("404");
    }


}