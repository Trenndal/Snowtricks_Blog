<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;

use Trenndal\SnowtricksBundle\Entity\EditTrick;
use Trenndal\SnowtricksBundle\Entity\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DefaultController extends Controller
{
	private function addTrick($em, $name, $description, $typeGroup, $images){
		$trick =  new EditTrick();
		$trick->setName($name);
		$trick->setDescription($description);
		$trick->setTypeGroup($typeGroup);
		foreach($images as $imageUrl){
			$image= new Image();
			$file = new File('/web/img/'.$imageUrl);
			$image->setTrick($trick);
			$image->setFile(new UploadedFile('/web/img/'.$imageUrl,'Picture',$file.getMimeType()));
			$trick->addImage($image);
		}
		$em->persist($trick);
		$em->flush();
	}
	
	private function addGrabs($em){
		$this->addTrick($em,'Ollie',"A trick in which the snowboarder springs off the tail of the board and into the air.",1,array('Ollie1.jpg','Ollie2.jpg','Ollie3.jpg'));
		$this->addTrick($em,'A B',"A trick in which the rider's rear hand grabs the heel side of the board front for the front bindings.",1,array('A B1.jpg','A B2.jpg'));
		$this->addTrick($em,'Bloody Dracula',"A trick in which the rider grabs the tail of the board with both hands. The rear hand grabs the board as it would do it during a regular tail-grab but the front hand blindly reaches for the board behind the riders back.",1,array('Bloody Dracula1.jpg','Bloody Dracula2.jpg'));
	}
	
	private function addFlips($em){
		$this->addTrick($em,'Back flip',"Flipping backwards (like a standing backflip) off of a jump.",4,array('Back flip1.jpg','Back flip2.jpg'));
		$this->addTrick($em,'McTwist',"A forward-flipping backside 540, performed in a halfpipe, quarterpipe, or similar obstacle. The rotation may continue beyond 540° (e.g., McTwist 720). The origin of this trick comes from vert ramp skateboarding, and was first performed on a skateboard by Mike McGill.",4,array('McTwist1.jpg','McTwist2.jpg'));
	}
	
	private function addInverts($em){
		$this->addTrick($em,'Invert',"Overlaying term for handstands on the edge of a halfpipe",5,array('Invert1.jpg','Invert2.jpg'));
		$this->addTrick($em,'Elguerial',"An invert where the halfpipe wall is approached fakie, the rear hand is planted, a 360 degree backside rotation is made, and the rider lands going forward. Named after Eddie Elguera.",5,array('Elguerial1.jpg','Elguerial2.jpg'));
	}
	
	private function addSlides($em){
		$this->addTrick($em,'50-50',"A slide in which a snowboarder rides straight along a rail or other obstacle. This trick has its origin in skateboarding, where the trick is performed with both skateboard trucks grinding along a rail.",6,array('50-501.jpg','50-502.jpg'));
		$this->addTrick($em,'Boardslide',"A slide performed where the riders leading foot passes over the rail on approach, with his/her snowboard traveling perpendicular along the rail or other obstacle. When performing a frontside boardslide, the snowboarder is facing uphill. When performing a backside boardslide, a snowboarder is facing downhill. This is often confusing to new riders learning the trick because with a frontside boardslide you are moving backward and with a backside boardslide you are moving forward.",6,array('Boardslide1.jpg','Boardslide2.jpg'));
	}
	
    /**
     * @Route("/installer", name="installer")
     */
    public function installerAction(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		$this->addGrabs($em);
		$this->addFlips($em);
		$this->addInverts($em);
		$this->addSlides($em);
        return $this->redirect('/tricks/');
    }
}
