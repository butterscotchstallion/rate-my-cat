<?php

namespace PrgmrBill\RateMyCatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \PrgmrBill\RateMyCatBundle\Model\CatsQuery;
use \PrgmrBill\RateMyCatBundle\Model\CatPicturesQuery;
use \PrgmrBill\RateMyCatBundle\Model\CatRatingsQuery;
use \Propel;

class CatController extends Controller
{ 
    public function indexAction()
    {
        $cats = CatsQuery::getCats();
        
        // If there are some cats, get ratings for those cats
        if ($cats) {
            $ratings       = CatRatingsQuery::getRatings();            
            $pictureCounts = CatPicturesQuery::getPictureCounts();
            
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
