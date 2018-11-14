<?php
namespace Coordinates;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;

class Main extends PluginBase {

	private $coord = [];

	public function onEnable(){
		$this->getLogger()->info("Coordinates plugin has been Enabled !");
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if($command->getName() === "coordinates"){
			if(!$sender->hasPermission("coordinates.command")){
				$sender->sendMessage("§cYou do not have permission to use this command");
				return false;
			}
			if(empty($args[0])){
				if(!in_array($sender->getName(), $this->coord)){
					$this->coord[] = $sender->getName();
					$pk = new GameRulesChangedPacket();
        				$pk->gameRules = ["showcoordinates" => [1, true]];
        				$sender->getPlayer()->dataPacket($pk);
                    $sender->sendMessage("§eCoordinates has been enabled!");
				}elseif(in_array($sender->getName(), $this->coord)){
					unset($this->coord[array_search($sender->getName(), $this->coord)]);
					$pk = new GameRulesChangedPacket();
        				$pk->gameRules = ["showcoordinates" => [1, false]];
        				$sender->getPlayer()->dataPacket($pk);
                    $sender->sendMessage("§eCoordinates has been disabled!");
				}
			}
			return false;
		}
	}
}