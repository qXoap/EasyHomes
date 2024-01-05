<?php

namespace qXoap\EasyHomes\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use qXoap\EasyHomes\manager\HomeManager;

class RemoveHome extends Command {

    public function __construct()
    {
        parent::__construct("rmhome", "Remove Home (qXoap)", null, ["delhome"]);
        $this->setPermission("easyhomes.command.removehome");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if(!$player instanceof Player)return;

        HomeManager::getInstance()->getRemoveHomes($player);
    }
}
