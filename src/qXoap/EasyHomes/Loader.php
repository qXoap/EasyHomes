<?php

namespace qXoap\EasyHomes;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use qXoap\EasyHomes\commands\AddHome;
use qXoap\EasyHomes\commands\HomesList;
use qXoap\EasyHomes\commands\RemoveHome;
use qXoap\EasyHomes\manager\HomeManager;

class Loader extends PluginBase {
    use SingletonTrait;

    protected function onLoad(): void
    {
        self::setInstance($this);
    }

    protected function onEnable(): void
    {
        $this->saveResource("config.yml");
        $this->saveResource("messages.yml");
        HomeManager::prepare();
        $map = Server::getInstance()->getCommandMap();
        $commands = [
            new AddHome(),
            new HomesList(),
            new RemoveHome()
        ];
        $map->registerAll("easyhomes", $commands);
    }

    public function getHomeConfig(string $config)
    {
        $file = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        return $file->get($config);
    }

    public function getHomeMesages(string $message)
    {
        $file = new Config($this->getDataFolder() . "messages.yml", Config::YAML);
        return $file->get($message);
    }
}