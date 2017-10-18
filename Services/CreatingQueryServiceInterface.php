<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 17.10.2017 г.
 * Time: 8:55
 */

namespace Employees\Services;


use Employees\Models\Binding\Emp\EmpBindingModel;

interface CreatingQueryServiceInterface
{
    public function setQueryUpdateEmp(EmpBindingModel $bindingModel);

    public function getQuery() : string;

    public function getValues() : array;
}