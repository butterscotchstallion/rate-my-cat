<?php

namespace PrgmrBill\RateMyCatBundle\Model;

use PrgmrBill\RateMyCatBundle\Model\om\BaseCatsQuery;

class CatsQuery extends BaseCatsQuery
{
    static public function getCats()
    {
        $conn = \Propel::getConnection(CatsPeer::DATABASE_NAME);
        
        // Get cats
        $query = "SELECT c.id,
                         c.name
                  FROM cats c
                  ORDER BY c.name";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $cats = $stmt->fetchAll();
        
        return $cats;
    }
    
    static public function getCatByID($catID)
    {
        $cat = array();
        
        if ($catID) {
            $conn = \Propel::getConnection(CatsPeer::DATABASE_NAME);
            
            $query = 'SELECT c.id,
                             c.name,
                             c.created_at AS createdAt,
                             ROUND(AVG(cr.rating),2) AS rating,
                             COUNT(*) as voteCount
                      FROM cats c
                      LEFT JOIN cat_ratings cr ON cr.cat_id = c.id
                      WHERE 1=1
                      AND c.id = :id';
            $stmt = $conn->prepare($query);
            $stmt->execute(array(':id' => $catID));
            $cat = $stmt->fetch();
            
            return $cat;
        }
        
        return $cat;
    }
}
