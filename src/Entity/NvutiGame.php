<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NvutiGameRepository")
 */
class NvutiGame extends Game
{
    const MIN_NUMBER = 0;

    const MAX_NUMBER = 999999;

    const STAKE_LESS = 'less';

    const STAKE_MORE = 'more';

    const STATUS_PENDING = 'pending';

    const STATUS_CLOSED = 'closed';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

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
}
