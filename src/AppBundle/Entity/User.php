<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity("email")
 */
class User implements UserInterface
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
     * @codeCoverageIgnore
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * @codeCoverageIgnore
     */
    public function eraseCredentials()
    {
    }
}
