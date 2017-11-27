<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 26.11.2017 г.
 * Time: 12:46
 */

namespace Employees\Services;


use Employees\Models\Binding\News\NewsBindingModel;

interface NewsServiceInterface
{
    public function getAllNews($isActive);

    public function getNews($id);

    public function addNews(NewsBindingModel $newsBindingModel) : bool;

    public function updateNews();

    public function removeNews($id);
}