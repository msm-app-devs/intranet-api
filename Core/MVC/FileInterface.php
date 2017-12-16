<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 15.12.2017 г.
 * Time: 13:25
 */

namespace Employees\Core\MVC;


interface FileInterface
{

    public function uploadFile($tmp_path) : bool;

    public function removeFile() : bool;

    public function fileExists() : bool;

}