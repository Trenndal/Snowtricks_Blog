<?php

namespace Trenndal\SnowtricksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('TrenndalSnowtricksBundle:Default:index.html.twig');
    }
	
    /**
     * @Route("/page/{slug}")
     */
    public function pageAction($slug)
    {
        return $this->render('TrenndalSnowtricksBundle:Default:page.html.twig',array('title'=>$slug));
    }
}
