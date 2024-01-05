<?php

namespace qXoap\EasyHomes\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use qXoap\EasyHomes\Loader;
use qXoap\EasyHomes\manager\HomeManager;

class AddHome extends Command {

    public function __construct()
    {
        parent::__construct("addhome", "Add New Home (qXoap)", null, ["sethome"]);
        $this->setPermission("easyhomes.command.addhome");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if(!$sender instanceof Player)return;

        if(!isset($args[0])){
            $sender->sendMessage(HomeManager::getPrefix()."Usage /addhome (name)");
            return;
        }

        $name = implode(" ", $args);

        if(HomeManager::getInstance()->isHomeExist($sender, $name)){
            $sender->sendMessage(HomeManager::getPrefix().Loader::getInstance()->getHomeMesages("home-exist-message"));
            return;
        }

        $maxhomes = Loader::getInstance()->getHomeConfig("max-homes");

        if(count(HomeManager::getInstance()->getHomes($sender)->getAll(true)) >= $maxhomes){
            $msg = Loader::getInstance()->getHomeMesages("homes-limit-message");
            $msg = str_replace("{MAXHOMES}", $maxhomes, $msg);
            $sender->sendMessage(HomeManager::getPrefix().TextFormat::colorize($msg));
            return;
        }

        HomeManager::getInstance()->setHome($sender, $name, $sender->getPosition());
        $msg = Loader::getInstance()->getHomeMesages("created-home-message");
        $msg = str_replace("{HOME}", $name, $msg);
        $sender->sendMessage(HomeManager::getPrefix().TextFormat::colorize($msg));

    }
}
