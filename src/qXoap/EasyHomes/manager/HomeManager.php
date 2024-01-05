<?php

namespace qXoap\EasyHomes\manager;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;
use qXoap\EasyHomes\libs\SimpleForm;
use qXoap\EasyHomes\Loader;

class HomeManager
{
    use SingletonTrait;

    public function __construct()
    {
        self::setInstance($this);
    }

    public static function prepare(): void
    {
        if(!is_dir(Loader::getInstance()->getDataFolder() . "data/")){
            mkdir(Loader::getInstance()->getDataFolder() . "data/");
        }
    }

    public function getHomes(Player $player): Config
    {
        return new Config(Loader::getInstance()->getDataFolder() . "data/".$player->getName().".yml", Config::YAML);
    }

    public function isHomeExist(Player $player, string $name): bool
    {
        $file = Loader::getInstance()->getDataFolder() . "data/".$player->getName().".yml";
        if(file_exists($file)){
            if($this->getHomes($player)->exists($name)){
                return true;
            }
        }
        return false;
    }

    public static function getPrefix(): string
    {
        return TextFormat::colorize(Loader::getInstance()->getHomeMesages("prefix"));
    }

    public function setHome(Player $player, string $name, Position $position): void
    {
        $file = new Config(Loader::getInstance()->getDataFolder() . "data/".$player->getName().".yml", Config::YAML);
        $file->set($name, [
            "X" => $position->x,
            "Y" => $position->y,
            "Z" => $position->z,
            "world" => $position->getWorld()->getFolderName(),
            "name" => $name
        ]);
        $file->save();
    }

    public function removeHome(Player $player, string $name): void
    {
        $file = new Config(Loader::getInstance()->getDataFolder() . "data/".$player->getName().".yml", Config::YAML);
        $file->remove($name);
        $file->save();
    }

    public function getHomesForm(Player $player): void
    {
        $menu = new SimpleForm(function (Player $player, $data = null) {
            if ($data === null) {
                return;
            }

            if (count($this->getHomes($player)->getAll(true)) <= 0) {
                return;
            }

            if (is_null($this->getHomes($player)->getAll(true))) {
                return;
            }

            if (!$this->isHomeExist($player, $data)) {
                $player->sendMessage(self::getPrefix() . TextFormat::colorize(Loader::getInstance()->getHomeMesages("no-exist-home")));
                return;
            }

            $x = $this->getHomes($player)->get($data)["X"];
            $y = $this->getHomes($player)->get($data)["Y"];
            $z = $this->getHomes($player)->get($data)["Z"];
            $world = $this->getHomes($player)->get($data)["world"];
            $name = $this->getHomes($player)->get($data)["name"];
            $player->teleport(new Position($x, $y, $z, Server::getInstance()->getWorldManager()->getWorldByName($world)));
            $message = Loader::getInstance()->getHomeMesages("teleport-home-message");
            $message = str_replace("{HOME}", $name, $message);
            $player->sendMessage(self::getPrefix() . TextFormat::colorize($message));

        });
        $menu->setTitle("§8" . $player->getName() . "§5 Homes");
        if (count($this->getHomes($player)->getAll(true)) <= 0) {
            $menu->addButton("§l§4EXIT", 0, "textures/ui/redX1");
        } else {
            foreach ($this->getHomes($player)->getAll(true) as $home) {
                $menu->addButton($home . "\nTap To Teleport", 0, "textures/ui/icon_map", $home);
            }
        }
        $player->sendForm($menu);
    }

    public function getRemoveHomes(Player $player): void
    {
        $menu = new SimpleForm(function (Player $player, $data = null) {
            if ($data === null) {
                return;
            }

            if (count($this->getHomes($player)->getAll(true)) <= 0) {
                return;
            }

            if (!$this->isHomeExist($player, $data)) {
                $player->sendMessage(self::getPrefix() . TextFormat::colorize(Loader::getInstance()->getHomeMesages("no-exist-home")));
                return;
            }

            $name = $this->getHomes($player)->get($data)["name"];
            $msg = Loader::getInstance()->getHomeMesages("remove-home-message");
            $msg = str_replace("{HOME}", $name, $msg);
            $this->removeHome($player, $data);
            $player->sendMessage(self::getPrefix() . TextFormat::colorize($msg));

        });
        $menu->setTitle("§8" . $player->getName() . "§c Homes");
        if (count($this->getHomes($player)->getAll(true)) <= 0) {
            $menu->addButton("§l§4EXIT", 0, "textures/ui/redX1");
        } else {
            foreach ($this->getHomes($player)->getAll(true) as $home) {
                $menu->addButton($home . "\nTap To Remove Home", 0, "textures/ui/icon_none", $home);
            }
        }
        $player->sendForm($menu);
    }
}
