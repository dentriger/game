<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(name="registration_ip", type="string", length=255, nullable=true)
     */
    private $registrationIp;

    /**
     * @ORM\Column(name="last_ip", type="string", length=255, nullable=true)
     */
    private $lastIp;

    /**
     * @ORM\Column(name="user_ips", type="array", nullable=true)
     */
    private $userIps = [];

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $registration_time;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Wallet", mappedBy="user")
     */
    private $wallets;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Referals", inversedBy="users")
     */
    private $referals;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $referalLink;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="user_id", orphanRemoval=true)
     */
    private $messages;

    public function __construct()
    {
        $this->wallets = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

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
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return mixed
     */
    public function getRegistrationIp()
    {
        return $this->registrationIp;
    }

    /**
     * @param mixed $registrationIp
     */
    public function setRegistrationIp($registrationIp): void
    {
        $this->registrationIp = $registrationIp;
    }

    /**
     * @return mixed
     */
    public function getLastIp()
    {
        return $this->lastIp;
    }

    /**
     * @param mixed $lastIp
     */
    public function setLastIp($lastIp): void
    {
        $this->lastIp = $lastIp;
    }

    /**
     * @return array
     */
    public function getUserIps(): array
    {
        return $this->userIps;
    }

    /**
     * @param array $user_ips
     */
    public function setUserIps(array $userIps): void
    {
        $this->userIps = $userIps;
    }

    public function addUserIp($userIp) {
        if (!in_array($userIp, $this->userIps)) {
            $this->userIps[] = $userIp;
        }

        return $this;
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
            $this->isActive,
            $this->roles,
            $this->registrationIp,
            $this->lastIp,
            $this->userIps,
            $this->registration_time,
            $this->wallets,
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
            $this->isActive,
            $this->roles,
            $this->registrationIp,
            $this->lastIp,
            $this->userIps,
            $this->registration_time,
            $this->wallets,
            ) = unserialize($serialized);
    }

    /**
     * @return mixed
     */
    public function getRegistrationTime()
    {
        return $this->registration_time;
    }

    /**
     * @param mixed $registration_time
     */
    public function setRegistrationTime($registration_time): void
    {
        $this->registration_time = $registration_time;
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

    /**
     * @return Collection|Wallet[]
     */
    public function getWallets(): Collection
    {
        return $this->wallets;
    }

    public function setWallets(?Wallet $wallet): self
    {
        $this->wallets[] = $wallet;

        return $this;
    }

    public function addWallet(Wallet $wallet): self
    {
        if (!$this->wallets->contains($wallet)) {
            $this->wallets[] = $wallet;
            $wallet->setUser($this);
        }

        return $this;
    }

    public function removeWallet(Wallet $wallet): self
    {
        if ($this->wallets->contains($wallet)) {
            $this->wallets->removeElement($wallet);
            // set the owning side to null (unless already changed)
            if ($wallet->getUser() === $this) {
                $wallet->setUser(null);
            }
        }

        return $this;
    }

    public function getReferals(): ?Referals
    {
        return $this->referals;
    }

    public function setReferals(?Referals $referals): self
    {
        $this->referals = $referals;

        return $this;
    }

    public function getReferalLink(): ?string
    {
        return $this->referalLink;
    }

    public function setReferalLink(string $referalLink): self
    {
        $this->referalLink = $referalLink;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }
}
