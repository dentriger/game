<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NvutiGameBetRepository")
 */
class NvutiGameBet extends Bet
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
    private $numbers_range;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumbersRange(): ?string
    {
        return $this->numbers_range;
    }

    public function setNumbersRange(string $numbers_range): self
    {
        $this->numbers_range = $numbers_range;

        return $this;
    }
}
