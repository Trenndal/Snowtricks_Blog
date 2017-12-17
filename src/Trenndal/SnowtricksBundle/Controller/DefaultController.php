<?php

namespace Trenndal\SnowtricksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Trenndal\SnowtricksBundle\Entity\EditTrick;
use Trenndal\SnowtricksBundle\Form\EditTrickType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('TrenndalSnowtricksBundle:Default:index.html.twig');
    }
	
    /**
     * @Route("/tricks/")
     */
    public function tricksAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tricks = $em->getRepository('TrenndalSnowtricksBundle:EditTrick')->findAll();
        return $this->render('TrenndalSnowtricksBundle:Default:tricks.html.twig',array('tricks'=>$tricks));
    }
	
    /**
     * @Route("/trick/{slug}")
     */
    public function trickAction($slug)
    {
		$em = $this->getDoctrine()->getManager();
		$trick = $em->getRepository('TrenndalSnowtricksBundle:EditTrick')->find($slug);
		if (null === $trick) {
			throw new NotFoundHttpException("L'annonce d'id ".$slug." n'existe pas.");
		}
		
		
        return $this->render('TrenndalSnowtricksBundle:Default:trick.html.twig',array('title'=>$trick->getName(), 'trick'=>$trick));
    }
	
    /**
     * @Route("/edit/{slug}")
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction($slug, Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		if($slug>0){
			$trick = $em->getRepository('TrenndalSnowtricksBundle:EditTrick')->find($slug);
			$title=$trick->getName();
		} else {
			$title='New snowtrick';
			$trick =  new EditTrick();
		}
		if (null === $trick) {
			$request->getSession()->getFlashBag()->add('notice', 'Trick not found.');
			return $this->redirect('/tricks/');
		}
		
		$formBuilder = $this->get('form.factory')->createBuilder(EditTrickType::class, $trick);
		$form = $formBuilder->getForm();
		
		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($trick);
				foreach($trick->getImages() as $image){ $image->setTrick($trick);}
				$em->flush();
				return $this->redirect('/trick/'.$trick->getId());
			}
		}
		
        return $this->render('TrenndalSnowtricksBundle:Default:edit.html.twig',array('title'=>$title, 'form' => $form->createView()));
    }
}
