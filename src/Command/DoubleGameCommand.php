<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 23.09.2018
 * Time: 18:53
 */

namespace App\Command;


use App\Repository\BetRepository;
use App\Repository\GameRepository;
use App\Repository\WalletRepository;
use App\Services\DoubleGame;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DoubleGameCommand extends Command
{
    protected static $defaultName = 'double:start';

    protected $gameRepository;

    protected $betRepository;

    protected $walletRepository;

    public function __construct(GameRepository $gameRepository, BetRepository $betRepository, WalletRepository $walletRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->betRepository = $betRepository;
        $this->walletRepository = $walletRepository;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $double_game = new DoubleGame($this->gameRepository, $this->betRepository, $this->walletRepository);

        $double_game->startGame();
    }
}