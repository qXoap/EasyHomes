<?php

namespace qXoap\EasyHomes\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use qXoap\EasyHomes\manager\HomeManager;

class HomesList extends Command {

    public function __construct()
    {
        parent::__construct("homes", "Home List (qXoap)", null, ["home"]);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if(!$player instanceof Player)return;

        HomeManager::getInstance()->getHomesForm($player);
    }
}