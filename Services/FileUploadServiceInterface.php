<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 12.12.2017 г.
 * Time: 15:43
 */

namespace Employees\Services;


interface FileUploadServiceInterface
{
    public function uploadFile($tmp_path, $path, $fileName) : bool;

    public function removeFile($fileName, $path) : bool;

    public function fileExists($path, $fileName) : bool;


}