<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 12.12.2017 г.
 * Time: 15:05
 */


namespace Employees\Services;


class FileUploadService implements FileUploadServiceInterface
{
//    private $name;
//    private $path;
//    private $type;
//    /**
//     * File constructor.
//     * @param $name
//     * @param $path
//     * @param $size
//     * @param $type
//     */
//    public function __construct($name, $path, $type)
//    {
//        $this->name = $name;
//        $this->path = $path;
//        $this->type = $type;
//    }

    public function uploadFile($tmp_path, $path, $fileName) : bool
    {
        return move_uploaded_file($tmp_path, $path."/".$fileName);
    }

    public function removeFile($fileName, $path) : bool
    {
        return unlink($path."/".$fileName);
    }

    public function fileExists($path, $fileName) : bool
    {
        return file_exists($path."/".$fileName);
    }

}