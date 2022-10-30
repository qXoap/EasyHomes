<?php

namespace qXoap\EasyHomes\manager;

use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\world\Position;
use qXoap\EasyHomes\Loader;

class HomeManager {

    public function getHomes(Player $player): Config
    {
        return new Config(Loader::getInstance()->getDataFolder() . "data/".$player->getName().".yml", Config::YAML);
    }

    public function isHomeExist(Player $player, string $name)
    {
        $file = Loader::getInstance()->getDataFolder() . "data/".$player->getName().".yml";
        if(file_exists($file)){
            if($this->getHomes($player)->exists($name)){
                return true;
            }
        }
        return false;
    }

    public function setHome(Player $player, string $name, Position $position)
    {
        $file = new Config(Loader::getInstance()->getDataFolder() . "data/".$player->getName().".yml", Config::YAML);
        $file->set($name, [
            "X" => $position->x,
            "Y" => $position->y,
            "Z" => $position->z,
            "world" => $position->getWorld(),
        ]);
        $file->save();
    }

    public function removeHome(Player $player, string $name)
    {
        $file = new Config(Loader::getInstance()->getDataFolder() . "data/".$player->getName().".yml", Config::YAML);
        $file->remove($name);
        $file->save();
    }

    public function getHomesForm(Player $player): void
    {

    }
}