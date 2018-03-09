<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity("email")
 */
class User implements UserInterface, \Serializable, EquatableInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Vous devez saisir un nom d'utilisateur.")
     * @var string
     */
    private $username;

    /**
     * @Assert\NotBlank(message="Vous devez saisir un mot de passe.")
     * @var string
     */
    private $password;

    /**
     * @Assert\NotBlank(message="Vous devez saisir une adresse email.")
     * @Assert\Email(message="Le format de l'adresse n'est pas correcte.")
     * @var string
     */
    private $email;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var ArrayCollection
     */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->roles[] = 'ROLE_USER';
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
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param $username string
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return null
     * @codeCoverageIgnore
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password string
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email string
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param $roles array
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @codeCoverageIgnore
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return ArrayCollection
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param ArrayCollection $tasks
     */
    public function setTasks(ArrayCollection $tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * @param Task $task
     */
    public function addTask(Task $task)
    {
        $this->tasks->add($task);
        $task->setUser($this);
    }

    /**
     * @param Task $task
     */
    public function removeTask(Task $task)
    {
        $this->tasks->removeElement($task);
    }

    /* SERIALIZABLE INTERFACE */
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->username,
            $this->password,
            $this->roles
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->username,
            $this->password,
            $this->roles
            ) = unserialize($serialized);
    }

    /* EQUATABLE INTERFACE */
    /**
     * @param UserInterface $user
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        if ($this->getPassword() !== $user->getPassword()) {
            return false;
        }

        if ($this->getUsername() !== $user->getUsername()) {
            return false;
        }

        return true;
    }
}
