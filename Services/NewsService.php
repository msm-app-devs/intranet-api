<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 26.11.2017 г.
 * Time: 12:46
 */

namespace Employees\Services;

use Employees\Adapter\DatabaseInterface;
use NewsBindingModel;


class NewsService implements NewsServiceInterface
{
    private $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }


    public function getAllNews($isActive)
    {
        $query = "SELECT * FROM news WHERE active = ?";

        $stmt = $this->db->prepare($query);

        $stmt->execute([$isActive]);

        $result = $stmt->fetchAll();

        return $result;
    }

    public function getNews($id)
    {
        $query = "SELECT
                 * FROM news
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        $stmt->execute([$id]);

        $result = $stmt->fetchAll();

        return $result;
    }

    public function addNews(NewsBindingModel $newsBindingModel) : bool
    {
        $query = "INSERT INTO 
                  news (
                  admin_id,
                  active,
                  date,
                  author,
                  title,
                  body,
                  image
                  ) 
                  VALUES (?,?,?,?,?,?,?)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $newsBindingModel->getAdminId(),
            "yes",
            $newsBindingModel->getDate(),
            $newsBindingModel->getAuthor(),
            $newsBindingModel->getTitle(),
            $newsBindingModel->getBody(),
            $newsBindingModel->getImage()
        ]);
    }

    public function updateNews()
    {

    }

    public function removeNews($id)
    {

    }


}