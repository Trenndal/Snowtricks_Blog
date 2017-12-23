<?php

namespace Trenndal\SnowtricksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;

use Trenndal\SnowtricksBundle\Entity\EditTrick;
use Trenndal\SnowtricksBundle\Form\EditTrickType;
use Trenndal\SnowtricksBundle\Entity\Comment;
use Trenndal\SnowtricksBundle\Form\CommentType;

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
    public function trickAction($slug, Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		$trick = $em->getRepository('TrenndalSnowtricksBundle:EditTrick')->find($slug);
		if (null === $trick) {
			throw new NotFoundHttpException("L'annonce d'id ".$slug." n'existe pas.");
		}
		if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->get('security.token_storage')->getToken()->getUser();
			$comment=new Comment();
			$comment->setAuthor($user);
			$comment->setTrick($trick);
			$formBuilder = $this->get('form.factory')->createBuilder(CommentType::class, $comment);
			$form = $formBuilder->getForm();
			if ($request->isMethod('POST')) {
				$form->handleRequest($request);
				if ($form->isSubmitted() && $form->isValid()) {
					$em->persist($comment);
					$em->flush();
					return $this->redirect('/trick/'.$slug);
				}
			}
			return $this->render('TrenndalSnowtricksBundle:Default:trick.html.twig',array('title'=>$trick->getName(),'form' => $form->createView(),  'trick'=>$trick ));
		}
		
        return $this->render('TrenndalSnowtricksBundle:Default:trick.html.twig',array('title'=>$trick->getName(), 'trick'=>$trick ));
    }
	
    /**
     * @Route("/delete/{slug}/{token}", name="delete_trick")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction($slug, $token, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$trick = $em->getRepository('TrenndalSnowtricksBundle:EditTrick')->find($slug);
		if($trick and $this->isCsrfTokenValid('intention', $token)) {
			$em->remove($trick);
			$em->flush();
		}
		return $this->redirect('/tricks/');
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
			if (null !== $trick) $title=$trick->getName();
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
			if ($form->isSubmitted() && $form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($trick);
				foreach($trick->getImages() as $image){ $image->setTrick($trick);}
				$em->flush();
				return $this->redirect('/trick/'.$trick->getId());
			}
		}
		
        return $this->render('TrenndalSnowtricksBundle:Default:edit.html.twig',array('title'=>$title, 'form' => $form->createView(),'trick'=>$trick));
    }
}
