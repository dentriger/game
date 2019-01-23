<?php
/**
 * Created by PhpStorm.
 * User: andrei
 * Date: 11/20/18
 * Time: 5:48 PM
 */

namespace App\Service;


use Symfony\Component\Process\Process;

class DaemonClass
{
    private $process;

    public function __construct($command)
    {
        $this->process = new Process($command);
    }

    public function start()
    {
        try {
            $this->process->start();

        } catch (\Exception $exception) {

        }
    }

    public function stop()
    {
        $this->process->stop();
    }
}