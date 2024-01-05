<?php

namespace qXoap\EasyHomes\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use qXoap\EasyHomes\Loader;
use qXoap\EasyHomes\manager\HomeManager;

class HomesList extends Command
{

    public function __construct()
    {
        parent::__construct("homes", "Home List (qXoap)", null, ["home"]);
        $this->setPermission("easyhomes.command.home");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender instanceof Player) return;

        HomeManager::getInstance()->getHomesForm($sender);
    }
}
