<?php

namespace Trenndal\SnowtricksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

use Trenndal\SnowtricksBundle\Entity\User;
use Trenndal\SnowtricksBundle\Form\UserType;

class LoginController extends Controller
{

    /**
     * @Route("/login/", name="login")
     */
    public function loginAction(Request $request)
    {
		if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')){return $this->redirect('/');}
		$authUtils = $this->get('security.authentication_utils');
		$error = $authUtils->getLastAuthenticationError();
		$lastPath = $request->headers->get('referer');
		if(null===$lastPath){$lastPath = '/';}
		$lastUsername = $authUtils->getLastUsername();
		
        return $this->render('TrenndalSnowtricksBundle:Default:login.html.twig',array('error' => $error, 'last_username' => $lastUsername, 'last_path' => $lastPath));
    }

    /**
     * @Route("/resetpassword/{id}/{code}", name="resetPassword")
     */
    public function resetPasswordAction($id, $code, Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('TrenndalSnowtricksBundle:User')->find($id);
		$errors = null;
		if (null !== $user and preg_replace('/[^A-Za-z0-9\-]/', '',$user->getPassword())==$code) {
			$encoderService = $this->container->get('security.password_encoder');
			$formBuilder = $this->get('form.factory')->createBuilder(UserType::class, $user);
			$form = $formBuilder->getForm();
			if ($request->isMethod('POST')) {
				$form->handleRequest($request);
				
				if ($form->isSubmitted() && $form->isValid()) {
					$password = $encoderService->encodePassword($user,$user->getPassword());
					$user->setPassword($password);
					
					$em->persist($user);
					$em->flush();
					$request->getSession()->getFlashBag()->add('error', 'Password successfully modified.');
					return $this->redirect('/login');
				} else {
					$errors = $form->getErrors();
				}
			}
			return $this->render('TrenndalSnowtricksBundle:Default:registration.html.twig',array('errors' => $errors, 'user_name' => $user->getUsername(), 'form' => $form->createView()));
		}
		return $this->redirect('/reset');
    }

	
    /**
     * @Route("/reset/", name="reset")
     */
    public function resetAction(Request $request)
    {
		$valid=null;
		if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')){return $this->redirect('/');}
		if ($request->isMethod('POST')) {
			$name=$request->get('_username');
			$em = $this->getDoctrine()->getManager();
			$user = $em->getRepository('TrenndalSnowtricksBundle:User')->findBy(array('username'=>$name));
			if (!empty($user) ){
				$message = (\Swift_Message::newInstance())->setSubject('Password forgotten')->setTo(array($user[0]->getEmail()))->setBody('<a href="'.$request->getSchemeAndHttpHost().'/resetpassword/'.$user[0]->getId().'/'.preg_replace('/[^A-Za-z0-9\-]/', '',$user[0]->getPassword()).'">Click Here to change your Password</a>', 'text/html');
				$mailer = $this->get('mailer');
				try{
					$mailer->send($message);
					//$mailer->getTransport()->getSpool()->flushQueue($this->get('swiftmailer.transport.real'));
				} catch (Exception $e) {
					$valid="Error : The email wasn't send";
				}
				
			}
			if (null===$valid) {$valid='A email was send';}
		}
        return $this->render('TrenndalSnowtricksBundle:Default:reset.html.twig',array('valid'=>$valid,'last_username' => null));
    }


    /**
     * @Route("/registration/", name="registration")
     */
    public function registrationAction(Request $request)
    {
		if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')){return $this->redirect('/');}
		
		$errors=null;
		$user = new User();
		$encoderService = $this->container->get('security.password_encoder');
		$formBuilder = $this->get('form.factory')->createBuilder(UserType::class, $user);
		$form = $formBuilder->getForm();
		
		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				$password = $encoderService->encodePassword($user,$user->getPassword());
				$user->setPassword($password);
				
				$em = $this->getDoctrine()->getManager();
				$em->persist($user);
				$em->flush();
				$request->getSession()->getFlashBag()->add('error', 'Account successfully created.');
				return $this->redirect('/login');
			} else {
				$errors = $form->getErrors();
			}
		}

        return $this->render('TrenndalSnowtricksBundle:Default:registration.html.twig',array('errors' => $errors, 'user_name' => null, 'form' => $form->createView()));
    }

}
