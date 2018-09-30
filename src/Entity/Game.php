<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $time;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $gameHash;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $gameSalt;

    /**
     * @ORM\Column(type="float")
     */
    protected $gameNumber;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTime(): ?\DateTime
    {
        return $this->time;
    }

    public function setTime(\DateTime $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGameHash()
    {
        return $this->gameHash;
    }

    /**
     * @param mixed $gameHash
     */
    public function setGameHash($gameHash): void
    {
        $this->gameHash = $gameHash;
    }

    /**
     * @return mixed
     */
    public function getGameSalt()
    {
        return $this->gameSalt;
    }

    /**
     * @param mixed $gameSalt
     */
    public function setGameSalt($gameSalt): void
    {
        $this->gameSalt = $gameSalt;
    }

    /**
     * @return mixed
     */
    public function getGameNumber()
    {
        return $this->gameNumber;
    }

    /**
     * @param mixed $gameNumber
     */
    public function setGameNumber($gameNumber): void
    {
        $this->gameNumber = $gameNumber;
    }
}
