<?php

namespace PrgmrBill\RateMyCatBundle\Model;

use PrgmrBill\RateMyCatBundle\Model\om\BaseCatRatingsQuery;

class CatRatingsQuery extends BaseCatRatingsQuery
{
    static public function getRatingByCatID($catID) 
    {
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
            $tmpRatings = $stmt->fetchAll();
            $ratings    = array();
            
            // Build a lookup array for the rating of each cat
            if ($tmpRatings) {
                foreach ($tmpRatings as $r) {
                    $ratings[$r['catID']] = $r;
                }
            }
        }
        
        return $rating;
    }
    
    static public function getRatings()
    {
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
