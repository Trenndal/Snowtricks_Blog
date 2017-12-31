<?php

namespace Trenndal\SnowtricksBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Trenndal\SnowtricksBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * 
 */
class User implements UserInterface
{
	public function __construct() {
		$this->salt = "";
		$this->file = null;
		$this->roles = array('ROLE_USER');
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
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "You must specify at least one role"
     * )
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="Trenndal\SnowtricksBundle\Entity\Comment", mappedBy="author")
     */
    private $comments;

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


    /**
     * @Assert\File(
     *     maxSize = "3M",
     *     mimeTypes = {"image/bmp", "image/jpeg", "image/png", "image/gif", "image/jpg"},
     *     maxSizeMessage = "Please upload images under 3Mo of size ",
     *     mimeTypesMessage = "Please upload valid images "
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
        return "web/uploads/images/A".$this->id.'.'.$this->url;
    }


    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function update() { 
        if (null === $this->file) { return; }
        $this->preRemoveFile(); $this->removeFile();
        
        $this->file->move(__DIR__."/../../../../web/uploads/images/",'A'.$this->id.'.'.$this->file->guessExtension());
        
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


	public function eraseCredentials(){
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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return User
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
     * @return User
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
     * Add comment
     *
     * @param \Trenndal\SnowtricksBundle\Entity\Comment $comment
     *
     * @return User
     */
    public function addComment(\Trenndal\SnowtricksBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \Trenndal\SnowtricksBundle\Entity\Comment $comment
     */
    public function removeComment(\Trenndal\SnowtricksBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }
}
