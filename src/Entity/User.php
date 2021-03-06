<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUser;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $uid;

    /**
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo_rec;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = ['ROLE_USER'];

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = false;

    /**
     * @ORM\Column(name="is_test", type="boolean")
     */
    private $isTest = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPhotoRec(): ?string
    {
        return $this->photo_rec;
    }

    public function setPhotoRec(string $photo_rec): self
    {
        $this->photo_rec = $photo_rec;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getIsTest()
    {
        return $this->isTest;
    }

    /**
     * @param mixed $isTest
     */
    public function setIsTest($isTest): void
    {
        $this->isTest = $isTest;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->uid,
            $this->firstName,
            $this->lastName,
            $this->photo,
            $this->photo_rec,
            $this->email,
            $this->isTest,
            $this->isActive,
            $this->roles
            // see section on salt below
            // $this->salt,
        ));
    }
    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->uid,
            $this->firstName,
            $this->lastName,
            $this->photo,
            $this->photo_rec,
            $this->email,
            $this->isTest,
            $this->isActive,
            $this->roles
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        return $this->getUid();
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
