<?php

namespace qXoap\EasyHomes;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class Loader extends PluginBase {
    use SingletonTrait;

    protected function onLoad(): void
    {
        self::setInstance($this);
    }

    protected function onEnable(): void
    {
        $this->saveResource("config.yml");
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