<?php

namespace Trenndal\SnowtricksBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Video
 *
 * @ORM\Table(name="video")
 * @ORM\Entity(repositoryClass="Trenndal\SnowtricksBundle\Repository\VideoRepository")
 */
class Video
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Trenndal\SnowtricksBundle\Entity\EditTrick", inversedBy="videos")
	 * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $trick;

    /**
     * @var int
     *
     * @ORM\Column(name="width", type="integer")
     */
    private $width;

    /**
     * @var int
     *
     * @ORM\Column(name="height", type="integer")
     */
    private $height;

    /**
     * @var string
     *
     * @ORM\Column(name="src", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $src;

    /**
     * @var string
     *
     * @ORM\Column(name="typeVideo", type="string", length=255)
     */
    private $typeVideo;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255, nullable=true)
     */
    private $alt;


    /**
     * @Assert\File(
     *     maxSize = "30M",
     *     mimeTypes = {"video/mpeg", "video/ogg", "video/mp4", "video/quicktime", "video/x-ms-wmv", "video/x-msvideo", "video/x-flv"},
     *     maxSizeMessage = "Please upload videos under 30Mo of size ",
     *     mimeTypesMessage = "Please upload valid videos "
     * )
     */
    private $file;


    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        if (null === $this->file) { return; }
        $this->url=$this->file->guessExtension();
        $this->alt=$this->file->getClientOriginalName();
    }

    public function getWebUrl()
    {
        return "web/uploads/images/".$this->id.'.'.$this->url;
    }


    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function update() {
        if (null === $this->file) { return; }
        $this->preRemoveFile(); $this->removeFile();
        
        $this->file->move(__DIR__."/../../../../web/uploads/images/",$this->id.'.'.$this->file->guessExtension());
        
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveFile() {
        
        $this->tempUrl = __DIR__."/../../../../".$this->getWebUrl();
        
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeFile() {
        
        if (file_exists($this->tempUrl)) { unlink($this->tempUrl); }
        
    }

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
     * Set width
     *
     * @param integer $width
     *
     * @return Video
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return Video
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set src
     *
     * @param string $src
     *
     * @return Video
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Get src
     *
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Set typeVideo
     *
     * @param string $typeVideo
     *
     * @return Video
     */
    public function setTypeVideo($typeVideo)
    {
        $this->typeVideo = $typeVideo;

        return $this;
    }

    /**
     * Get typeVideo
     *
     * @return string
     */
    public function getTypeVideo()
    {
        return $this->typeVideo;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Video
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }



    /**
     * Set trick
     *
     * @param \Trenndal\SnowtricksBundle\Entity\EditTrick $trick
     *
     * @return Video
     */
    public function setTrick(\Trenndal\SnowtricksBundle\Entity\EditTrick $trick)
    {
        $this->trick = $trick;

        return $this;
    }

    /**
     * Get trick
     *
     * @return \Trenndal\SnowtricksBundle\Entity\EditTrick
     */
    public function getTrick()
    {
        return $this->trick;
    }
}
