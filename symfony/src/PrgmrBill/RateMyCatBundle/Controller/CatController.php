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
        /*
        $cats = CatsQuery::create()
            ->joinWith('CatPictures')
            ->withColumn('CatPictures.filename', 'filename')
            ->withColumn('CatPictures.width', 'width')
            ->withColumn('CatPictures.height', 'height')
            ->find();
        */
        
        $con = Propel::getConnection(CatsPeer::DATABASE_NAME);
        $sql = "SELECT cats.id,
                       cats.name,
                       cats.created_at,
                       cat_pictures.cat_id,
                       cat_pictures.filename,
                       cat_pictures.width,
                       cat_pictures.height
                FROM `cats` INNER JOIN `cat_pictures` ON (cats.id=cat_pictures.cat_id)";
        
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $cats = $stmt->fetchAll();
        
        //print_r($cats);
        
        return $this->render('PrgmrBillRateMyCatBundle:Default:index.html.twig', array(
            'cats' => $cats
        ));
    }
}
