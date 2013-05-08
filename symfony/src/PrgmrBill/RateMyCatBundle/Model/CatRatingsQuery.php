<?php

namespace PrgmrBill\RateMyCatBundle\Model;

use PrgmrBill\RateMyCatBundle\Model\om\BaseCatRatingsQuery;

class CatRatingsQuery extends BaseCatRatingsQuery
{
    static public function getRatingByCatID($catID) 
    {
        $conn   = \Propel::getConnection(CatsPeer::DATABASE_NAME);
        $rating = array();
        
        if ($catID) {
            $query = 'SELECT cr.cat_id AS catID,
                             ROUND(AVG(rating),2) AS rating,
                             COUNT(*) as voteCount
                      FROM cat_ratings cr
                      WHERE cr.cat_id = :catID';
            
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':catID', $catID, \PDO::PARAM_INT);
            $stmt->execute();
            $rating = $stmt->fetch();            
        }
        
        return $rating;
    }

    static public function getTotalRatingsByCatID($catID)
    {
        $conn  = \Propel::getConnection(CatsPeer::DATABASE_NAME);
        $query = "SELECT COUNT(*) AS totalVotes
                  FROM cat_ratings cr
                  WHERE 1=1
                  AND cr.cat_id = :catID";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':catID', $catID, \PDO::PARAM_INT);
        $stmt->execute();
        $votes = $stmt->fetch();
        
        return $votes ? $votes['totalVotes'] : 0;
    }
    
    static public function getRatingTotalByCatID($catID) 
    {
        $conn  = \Propel::getConnection(CatsPeer::DATABASE_NAME);
        $query = "SELECT SUM(cr.rating) AS total
                  FROM cat_ratings cr
                  WHERE 1=1
                  AND cr.cat_id = :catID";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':catID', $catID, \PDO::PARAM_INT);
        $stmt->execute();        
        return $stmt->fetch();
    }
    
    static public function getRatingsGroupedByRatingByCatID($catID)
    {
        $conn  = \Propel::getConnection(CatsPeer::DATABASE_NAME);
        $query = "SELECT cr.rating,
                         COUNT(*) AS voteCount
                  FROM cat_ratings cr
                  WHERE 1=1
                  AND cr.cat_id = :catID
                  GROUP BY cr.rating
                  ORDER BY cr.cat_id";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':catID', $catID, \PDO::PARAM_INT);
        $stmt->execute();        
        return $stmt->fetchAll();
    }
    
    static public function getRatings()
    {
        $conn  = \Propel::getConnection(CatsPeer::DATABASE_NAME);
        $query = 'SELECT cr.cat_id AS catID,
                         ROUND(AVG(rating),2) AS rating,
                         COUNT(*) as voteCount
                  FROM cat_ratings cr
                  GROUP BY cr.cat_id';
        
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $tmpRatings = $stmt->fetchAll();
        $ratings    = array();
        
        // Build a lookup array for the rating of each cat
        if ($tmpRatings) {
            foreach ($tmpRatings as $r) {
                $ratings[$r['catID']] = $r;
            }
        }
        
        return $ratings;
    }
}
