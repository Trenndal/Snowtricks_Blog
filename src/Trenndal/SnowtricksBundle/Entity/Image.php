<?php

namespace Trenndal\SnowtricksBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="Trenndal\SnowtricksBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
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
     * @ORM\ManyToOne(targetEntity="Trenndal\SnowtricksBundle\Entity\EditTrick", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $trick;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $url;

    private $tempUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255, nullable=true)
     */
    private $alt;


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
     * Set id
     *
     * @param integer $id
     *
     * @return Image
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set url
     *
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
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
     * @return Image
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
