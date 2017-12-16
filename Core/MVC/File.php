<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 15.12.2017 г.
 * Time: 13:03
 */

namespace Employees\Core\MVC;


class File implements FileInterface
{
    private $name;
    private $path;
    private $size;
    private $type;

    /**
     * File constructor.
     * @param $name
     * @param $path
     * @param $size
     * @param $type
     */
    public function __construct($name, $path, $size, $type)
    {
        $this->name = $name;
        $this->path = $path;
        $this->size = $size;
        $this->type = $type;
    }


    public function uploadFile($tmp_path) : bool
    {
        if (move_uploaded_file($tmp_path, $this->path."/".$this->name.".".$this->type)) {
            return true;
        }

        return false;
    }

    public function removeFile() : bool
    {
        return unlink($this->path."/".$this->name.".".$this->type);
    }

    public function fileExists() : bool
    {
        return file_exists($this->path."/".$this->name.".".$this->type);
    }

}