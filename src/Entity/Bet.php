<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class Bet
{

    public function __construct()
    {
       //$this->bet_time =
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $game_type;

    /**
     * @ORM\Column(type="integer")
     */
    protected $game_id;
    /**
     * @ORM\Column(type="float")
     */
    protected $amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $currency;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $bet_time;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $status;

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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

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
    public function getBetTime()
    {
        return $this->bet_time;
    }

    /**
     * @param mixed $bet_time
     */
    public function setBetTime($bet_time): void
    {
        $this->bet_time = $bet_time;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency): void
    {
        $this->currency = $currency;
    }
}
