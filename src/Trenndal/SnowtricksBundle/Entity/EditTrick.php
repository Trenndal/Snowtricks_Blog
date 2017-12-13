<?php

namespace Trenndal\SnowtricksBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Events;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EditTrick
 *
 * @ORM\Table(name="edit_trick")
 * @ORM\Entity(repositoryClass="Trenndal\SnowtricksBundle\Repository\EditTrickRepository")
 * @ORM\HasLifecycleCallbacks
 */
class EditTrick
{
	public function __construct() {
		$this->date = new \Datetime();
		$this->videos = new ArrayCollection();
		$this->images = new ArrayCollection();
	}
	
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="typeGroup", type="integer", nullable=true)
     */
    private $typeGroup;
	

    /**
     * @ORM\OneToMany(targetEntity="Trenndal\SnowtricksBundle\Entity\Image", mappedBy="trick", cascade={"all"})
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="Trenndal\SnowtricksBundle\Entity\Video", mappedBy="trick", cascade={"all"})
     */
    private $videos;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    private $description;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return EditTrick
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return EditTrick
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set typeGroup
     *
     * @param integer $typeGroup
     *
     * @return EditTrick
     */
    public function setTypeGroup($typeGroup)
    {
        $this->typeGroup = $typeGroup;

        return $this;
    }

    /**
     * Get typeGroup
     *
     * @return int
     */
    public function getTypeGroup()
    {
        return $this->typeGroup;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return EditTrick
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Add image
     *
     * @param \Trenndal\SnowtricksBundle\Entity\Image $image
     *
     * @return EditTrick
     */
    public function addImage(\Trenndal\SnowtricksBundle\Entity\Image $image)
    {
        $this->images[] = $image;
        $image->setTrick($this);

        return $this;
    }

    /**
     * Remove image
     *
     * @param \Trenndal\SnowtricksBundle\Entity\Image $image
     */
    public function removeImage(\Trenndal\SnowtricksBundle\Entity\Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add video
     *
     * @param \Trenndal\SnowtricksBundle\Entity\Video $video
     *
     * @return EditTrick
     */
    public function addVideo(\Trenndal\SnowtricksBundle\Entity\Video $video)
    {
        $this->videos[] = $video;
        $video->setTrick($this);

        return $this;
    }

    /**
     * Remove video
     *
     * @param \Trenndal\SnowtricksBundle\Entity\Video $video
     */
    public function removeVideo(\Trenndal\SnowtricksBundle\Entity\Video $video)
    {
        $this->videos->removeElement($video);
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideos()
    {
        return $this->videos;
    }

}
