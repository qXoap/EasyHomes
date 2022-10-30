<?php

namespace qXoap\EasyHomes\manager;

use Forms\FormAPI\SimpleForm;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\world\Position;
use qXoap\EasyHomes\Loader;

class HomeManager {

    public const PREFIX = "§8(§eEasyHomes§8) §7";

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
        $menu = new SimpleForm(function (Player $player, int $data = null){
            if($data === null){
                return;
            }

            if(!$this->isHomeExist($player, $data)){
                $player->sendMessage(self::PREFIX."");
                return;
            }
        });
        $menu->setTitle("§8".$player->getName()."§5 Homes");
        if(is_null($this->getHomes($player)->getAll(true))){
            $menu->addButton("§l§4EXIT",0,"textures/ui/redX1");
            return;
        }
        if(count($this->getHomes($player)->getAll(true)) < 0){
            $menu->addButton("§l§4EXIT",0,"textures/ui/redX1");
            return;
        }
        foreach ($this->getHomes($player)->getAll(true) as $home) {
            $menu->addButton($home."\nTap To Teleport",0,"textures/ui/icon_map", $home);
            return;
        }
        $player->sendForm($menu);
    }
}