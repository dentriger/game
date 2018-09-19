<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BetRepository")
 */
class Bet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $game_type;

    /**
     * @ORM\Column(type="integer")
     */
    private $game_id;
    /**
     * @ORM\Column(type="integer")
     */
    private $stake;

    /**
     * @ORM\Column(type="string")
     */
    private $anticipated_event;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getGameType(): ?string
    {
        return $this->game_type;
    }

    public function setGameType(string $game_type): self
    {
        $this->game_type = $game_type;

        return $this;
    }

    public function getStake(): ?int
    {
        return $this->stake;
    }

    public function setStake(int $stake): self
    {
        $this->stake = $stake;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGameId()
    {
        return $this->game_id;
    }

    /**
     * @param mixed $game_id
     */
    public function setGameId($game_id): void
    {
        $this->game_id = $game_id;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
