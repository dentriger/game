<?php
/**
 * Created by PhpStorm.
 * User: andrei
 * Date: 9/28/18
 * Time: 5:26 PM
 */

namespace App\Service;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class NvutiGameService
{
    const MIN_NUMBER = 0;

    const MAX_NUMBER = 999999;

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function createGame($user_id)
    {
        $game = new Game();


    }

    private function generateNumber()
    {
        return rand(self::MIN_NUMBER, self::MAX_NUMBER);
    }

    private function hashNumber()
    {

    }
}