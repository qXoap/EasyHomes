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
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if(!$player instanceof Player)return;

        if(!isset($args[0])){
            $player->sendMessage(HomeManager::getPrefix()."Usage /addhome (name)");
            return;
        }

        $name = implode(" ", $args);

        if(HomeManager::getInstance()->isHomeExist($player, $name)){
            $player->sendMessage(HomeManager::getPrefix().Loader::getInstance()->getHomeMesages("home-exist-message"));
            return;
        }

        $maxhomes = Loader::getInstance()->getHomeConfig("max-homes");

        if(count(HomeManager::getInstance()->getHomes($player)->getAll(true)) >= $maxhomes){
            $msg = Loader::getInstance()->getHomeMesages("homes-limit-message");
            $msg = str_replace("{MAXHOMES}", $maxhomes, $msg);
            $player->sendMessage(HomeManager::getPrefix().TextFormat::colorize($msg));
            return;
        }

        HomeManager::getInstance()->setHome($player, $name, $player->getPosition());
        $msg = Loader::getInstance()->getHomeMesages("created-home-message");
        $msg = str_replace("{HOME}", $name, $msg);
        $player->sendMessage(HomeManager::getPrefix().TextFormat::colorize($msg));

    }
}