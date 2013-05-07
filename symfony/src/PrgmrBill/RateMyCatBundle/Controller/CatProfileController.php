<?php

namespace PrgmrBill\RateMyCatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \PrgmrBill\RateMyCatBundle\Model\CatsQuery;
use \PrgmrBill\RateMyCatBundle\Model\CatPicturesQuery;
use \Propel;

class CatProfileController extends Controller
{ 
    public function votesAction($catID = 0)
    {
        if ($catID) {
            
            
            
            return $this->render('PrgmrBillRateMyCatBundle:Profile:votes.html.twig', array(
                
            ));
            
        } else {
            $this->throwCatNotFound();
        }
    }
    
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
        
        return $this->render('PrgmrBillRateMyCatBundle:Profile:index.html.twig', array(
            'cat'      => $cat,
            'pictures' => $pictures,
            'catID'    => $catID
        ));
    }
    
    private function throwCatNotFound()
    {
        throw $this->createNotFoundException("Sorry, we can't seem to find that cat.");
    }
}