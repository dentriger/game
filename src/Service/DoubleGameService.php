<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 30.09.2018
 * Time: 21:26
 */

namespace App\Service;


use App\Services\RandomOrg;
use RandomOrg\Random;

class DoubleGameService implements GameInterface
{

    private static $run = true;

    private static $stop = 3000;

    private static $isConfigurated = false;

    private static $random;

    public static function start()
    {
        if (!self::$isConfigurated) {
            self::configurateService();
        }

        try {
            do {
                $number = self::getRandomNumber();

            } while (self::$run);

        } catch (\Exception $exception) {
            self::restart();
        }

        //if game stop -> pause for some time

        //if game not start -> start game
        //tick timer to start the game
        //get win sector
        //send time to front
        //stop tick -> send data for roll -> close stakes
        // send win data
        //Get users their wins
        // restart game

        //if error close game -> return stakes to users
        //restart game
        //
    }

    public static function restart()
    {
        self::start();
    }

    public static function stop()
    {
        sleep(self::$stop);
    }

    private static function configurateService()
    {
        self::$random = new Random(env('RANDOM_ORG_API_KEY'));
        self::$isConfigurated = true;
    }

    private static function getRandomNumber()
    {
        $number = 0;
        try {
            $result = self::$random->generateIntegers(1, 3600, 7200, false);
            $number = $result['result']['random']['data'][0];
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return $number;
    }

    public static function createNewGame($number)
    {
        $params = new \stdClass();
        $params->number = self::parseNumber($number % 360);
        $params->salt = self::getSalt();
        $params->hash = hash('sha224', $params->number.$params->salt);
        $game = $this->gameRepository->createDoubleGame($params);

        return $game;
    }

    public static function parseNumber($deg) {
        foreach ($this->numbers_sectors as $key => $sector) {
            if (in_array($deg, range($sector[0], $sector[1], 1))) {
                return $key;
            }

            if ($key = 0) {
                if (in_array($deg, range($sector[0], $sector[1], 1)) || in_array($deg, range($sector[2], $sector[3], 1))) {
                    return $key;
                }
            }
        }
    }

    public static function getSalt()
    {
        $result = self::$random->generateStrings(1, 6);
        return $result['result']['random']['data'][0];
    }
}
