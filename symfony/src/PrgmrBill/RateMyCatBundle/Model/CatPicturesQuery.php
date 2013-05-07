<?php

namespace PrgmrBill\RateMyCatBundle\Model;

use PrgmrBill\RateMyCatBundle\Model\om\BaseCatPicturesQuery;

class CatPicturesQuery extends BaseCatPicturesQuery
{
    static public function getCatPicturesByCatID($catID)
    {
        $pictures = array();
        
        if ($catID) {
            $conn = \Propel::getConnection(CatsPeer::DATABASE_NAME);
            
            $query = "SELECT p.filename,
                             p.width,
                             p.height
                      FROM cat_pictures p
                      WHERE 1=1
                      AND p.cat_id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':id', $catID, \PDO::PARAM_INT);
            $stmt->execute();
            $pictures = $stmt->fetchAll();
        }
        
        return $pictures;
    }
}
