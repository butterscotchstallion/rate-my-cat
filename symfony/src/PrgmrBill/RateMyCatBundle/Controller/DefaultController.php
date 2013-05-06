<?php

namespace PrgmrBill\RateMyCatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PrgmrBillRateMyCatBundle:Default:index.html.twig', array('name' => $name));
    }
}
