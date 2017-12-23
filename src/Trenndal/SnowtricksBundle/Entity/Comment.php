<?php

namespace Trenndal\SnowtricksBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Events;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="Trenndal\SnowtricksBundle\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Comment
{
	public function __construct() {
		$this->date = new \Datetime();
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
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Trenndal\SnowtricksBundle\Entity\EditTrick", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $trick;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Trenndal\SnowtricksBundle\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $author;


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
     * Set text
     *
     * @param string $text
     *
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set trick
     *
     * @param \Trenndal\SnowtricksBundle\Entity\EditTrick $trick
     *
     * @return Comment
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

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Comment
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
     * Set author
     *
     * @param \Trenndal\SnowtricksBundle\Entity\User $author
     *
     * @return Comment
     */
    public function setAuthor(\Trenndal\SnowtricksBundle\Entity\User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Trenndal\SnowtricksBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
