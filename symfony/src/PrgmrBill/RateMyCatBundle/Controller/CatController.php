<?php

namespace PrgmrBill\RateMyCatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \PrgmrBill\RateMyCatBundle\Model\CatsQuery;
use \PrgmrBill\RateMyCatBundle\Model\CatPicturesPeer;
use \PrgmrBill\RateMyCatBundle\Model\CatsPeer;
use \Propel;

class CatController extends Controller
{ 
    public function indexAction()
    {
        $conn = Propel::getConnection(CatsPeer::DATABASE_NAME);
        
        // Get cats
        $query = "SELECT c.id,
                         c.name
                  FROM cats c
                  ORDER BY c.name";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $cats = $stmt->fetchAll();
        
        // If there are some cats, get ratings for those cats
        if ($cats) {
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
            
            // Build a lookup array for the picture count of each cat
            $query = "SELECT cp.cat_id AS catID,
                             COUNT(*) as pictureCount
                      FROM cat_pictures cp
                      GROUP BY catID";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $tmpPictureCounts = $stmt->fetchAll();
            $pictureCounts    = array();
            
            if ($tmpPictureCounts) {
                foreach ($tmpPictureCounts as $c) {
                    $pictureCounts[$c['catID']] = $c;
                }
            }
            
            // Add rating to each cat
            foreach ($cats as $key => $c) {
                $id                         = $c['id'];
                $cats[$key]['rating']       = $ratings[$id]['rating'];
                $cats[$key]['voteCount']    = $ratings[$id]['voteCount'];
                $cats[$key]['pictureCount'] = $pictureCounts[$id]['pictureCount'];
            }
        }
        
        return $this->render('PrgmrBillRateMyCatBundle:List:index.html.twig', array(
            'cats' => $cats
        ));
    }
}
