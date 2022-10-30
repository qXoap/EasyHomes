<?php

namespace qXoap\EasyHomes\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class AddHome extends Command {

    public function __construct()
    {
        parent::__construct("addhome", "Add New Home (qXoap)", null, ["sethome"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {

    }
}