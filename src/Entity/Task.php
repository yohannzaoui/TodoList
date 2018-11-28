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
     * @return string
     */
    public function getShortContent()
    {
        if (preg_match('/^.{1,100}\b/s', $this->content, $match)) {
            return $match[0];
        }

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
        if (isset($this->author)) {
            $this->author->removeTask($this);
        }

        $user->addTask($this);
        $this->author = $user;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ? User
    {
        return $this->author;
    }
}
