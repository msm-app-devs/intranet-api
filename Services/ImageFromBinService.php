<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 16.12.2017 г.
 * Time: 11:21
 */

namespace Employees\Services;


class ImageFromBinService implements ImageFromBinServiceInterface
{

    public function CreateImage($binaryData, $imageName, $imgType) : bool
    {
        $pattern = '/^data:image\/(png|jpeg);base64,/';
        $data = preg_replace($pattern, "", $binaryData);
        $data = base64_decode($data);

        $im = imagecreatefromstring($data);

        if ($im !== false) {

            file_put_contents('webroot/images/'.$imageName.'.'.$imgType, $data);
            return true;
        }

        return false;
    }

}