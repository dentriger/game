<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DoubleGameBetRepository")
 */
class DoubleGameBet extends Bet
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
    private $anticipated_event;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnticipatedEvent(): ?string
    {
        return $this->anticipated_event;
    }

    public function setAnticipatedEvent(string $anticipated_event): self
    {
        $this->anticipated_event = $anticipated_event;

        return $this;
    }
}
