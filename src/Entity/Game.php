<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
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
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $win_number;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $anticipated_event;

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

    public function getWinNumber(): ?int
    {
        return $this->win_number;
    }

    public function setWinNumber(?int $win_number): self
    {
        $this->win_number = $win_number;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnticipatedEvent()
    {
        return $this->anticipated_event;
    }

    /**
     * @param mixed $anticipated_event
     */
    public function setAnticipatedEvent($anticipated_event): void
    {
        $this->anticipated_event = $anticipated_event;
    }
}
