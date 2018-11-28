<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Task.
 *
 * @package App\Entity
 */
class Task
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $content;

    /**
     * @var bool
     */
    private $isDone;

    /**
     * @var User
     */
    private $author;

    /**
     * Task constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \Datetime();
        $this->isDone = false;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }

    /**
     * @return bool
     */
    public function isDone()
    {
        return $this->isDone;
    }

    public function toggle()
    {
        $this->isDone = !$this->isDone;
    }

    /**
     * @param User $user
     */
    public function setAuthor(User $user)
    {
        $this->author = $user;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }
}
