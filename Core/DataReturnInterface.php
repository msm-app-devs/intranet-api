<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 21.12.2017 г.
 * Time: 17:58
 */

namespace Employees\Core;


interface DataReturnInterface
{
    public function jsonData($theData);

    public function errorMessage($message);
}