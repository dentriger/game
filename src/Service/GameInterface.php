<?php
/**
 * Created by PhpStorm.
 * User: andrei
 * Date: 11/20/18
 * Time: 4:31 PM
 */

namespace App\Service;


interface GameInterface
{
    public static function start();
    public static function restart();
    public static function stop();

}