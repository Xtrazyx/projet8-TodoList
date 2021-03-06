<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Task
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \Datetime
     */
    private $createdAt;

    /**
     * @Assert\NotBlank(message="Vous devez saisir un titre.")
     * @var string
     */
    private $title;

    /**
     * @Assert\NotBlank(message="Vous devez saisir du contenu.")
     */
    private $content;

    /**
     * @var bool
     */
    private $isDone;

    /**
     * @var User
     */
    private $user;

    /**
     * Task constructor.
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->createdAt = new \Datetime();
        $this->isDone = false;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
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
     * @param $title string
     */
    public function setTitle($title)
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
     * @param $content string
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     */
    public function isDone()
    {
        return $this->isDone;
    }

    /**
     * Toggles the isDone value
     */
    public function toggle()
    {
        $this->isDone = !$this->isDone;
    }

    /**
     * Set isDone.
     *
     * @param bool $isDone
     *
     * @return Task
     */
    public function setIsDone($isDone)
    {
        $this->isDone = $isDone;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}
