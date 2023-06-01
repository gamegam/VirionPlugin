<?php

namespace gamegam\cmd;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class VCommand extends Command{
    
    public function __construct(){   
        parent::__construct("virions", "View the list of loaded Vrions", null, ["vr"]);     
        $this->setPermission("VrionsPlugin.permission");     
    }
    public function execute(CommandSender $p, string $label, array $args): bool{
        $api = Server::getInstance()->getPluginManager()->getPlugin("VrionsPlugin");
        if (! Server::getInstance()->isOP($p->getName())){
            return false;
        }
        $api->file($p);
        return false;
    }
}
