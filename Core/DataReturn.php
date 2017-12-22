<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 29.8.2017 г.
 * Time: 12:20
 */

namespace Employees\Core;


use Employees\Core\MVC\MVCContextInterface;

class DataReturn implements DataReturnInterface
{
    private $mvcContext;

    public function __construct(MVCContextInterface $MVCContext)
    {
        $this->mvcContext = $MVCContext;
    }


    public function jsonDataReturn($theData)
    {
        print_r(json_encode(array($this->mvcContext->getController() => $theData)));
    }


}