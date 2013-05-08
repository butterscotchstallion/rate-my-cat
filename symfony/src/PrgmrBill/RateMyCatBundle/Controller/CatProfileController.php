<?php

namespace PrgmrBill\RateMyCatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \PrgmrBill\RateMyCatBundle\Model\CatsQuery;
use \PrgmrBill\RateMyCatBundle\Model\CatPicturesQuery;
use \PrgmrBill\RateMyCatBundle\Model\CatRatingsQuery;
use \Propel;

class CatProfileController extends Controller
{ 
    public function indexAction($catID = 0)
    {
        // Get the following:
        // 1. basic cat info from cats table
        // 2. pictures
        // 3. ratings/number of votes
        $cat = CatsQuery::getCatByID($catID);
        
        if (!$cat) {
            $this->throwCatNotFound();
        }
        
        // Pictures
        $pictures = CatPicturesQuery::getCatPicturesByCatID($catID);
        
        /*
            [
                ['Firefox',   45.0],
                ['IE',       26.8],
                {
                    name: 'Chrome',
                    y: 12.8,
                    sliced: true,
                    selected: true
                },
                ['Safari',    8.5],
                ['Opera',     6.2],
                ['Others',   0.7]
            ]
        */
        $votes       = CatRatingsQuery::getRatingsGroupedByRatingByCatID($catID);
        $tmp         = array();
        $voteData    = '';
        
        // Build vote data string for charts
        if ($votes) {
            $tmpRatingTotal = CatRatingsQuery::getRatingTotalByCatID($catID);
            $ratingTotal    = $tmpRatingTotal['total'];
            
            foreach ($votes as $key => $v) {
                $percentage = $ratingTotal ? round($v['rating'] / $ratingTotal, 2) * 100 : 0;
                $tmp[]      = sprintf('["Rated %s", %s]', $v['rating'], $percentage);
            }
            
            $voteData = sprintf('[%s]', implode(',', $tmp));
        }
        
        $totalVotes = CatRatingsQuery::getTotalRatingsByCatID($catID);
        
        return $this->render('PrgmrBillRateMyCatBundle:Profile:index.html.twig', array(
            'cat'        => $cat,
            'pictures'   => $pictures,
            'catID'      => $catID,
            'voteData'   => $voteData,
            'totalVotes' => $totalVotes
        ));
    }
    
    private function throwCatNotFound()
    {
        throw $this->createNotFoundException("Sorry, we can't seem to find that cat.");
    }
}