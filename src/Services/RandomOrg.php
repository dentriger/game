<?php

namespace App\Services;


use RandomOrg\Random;

class RandomOrg
{
    private $random;

    private static $instance;

    private function __construct()
    {
        $this->random = new Random(env('RANDOM_ORG_API_KEY'));
    }

    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new RandomOrg();
        }

        return self::$instance;
    }

    public function generateIntegers($n, $min, $max, $replacement = false)
    {
        $result = $this->random->generateIntegers($n, $min, $max, $replacement);
        $number = $result['result']['random']['data'][0];

        return $number;
    }

    public function generateStrings($n, $length, $chars = '', $replacement = false, $signed = false)
    {
        $result = $this->random->generateStrings($n, $length, $chars, $replacement, $signed);
        $string = $result['result']['random']['data'][0];

        return $string;
    }
}
