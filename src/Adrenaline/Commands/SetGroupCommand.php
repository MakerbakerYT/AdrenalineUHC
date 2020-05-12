<?php

declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class SetGroupCommand extends BaseCommand{

	/**
	 * SetGroupCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "setgroup", "Sets a player's group", "/setgroup [player] [group]");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if(!$this->getPlugin()->getAPI()->isCommandDisabled($this->getName())){
			if($sender instanceof Player){
				if(in_array($this->getPlugin()->getAPI()->getGroup($sender), ["mod", "owner"]) or $sender->isOp()){
					$player = $sender->getServer()->getPlayer($args[0]);
					if($player === null){
						$sender->sendMessage("Invalid player");
						return false;
					}else{
						$data = $this->getPlugin()->getAPI()->getPlayerData($player);
						if($data["group"] === strtolower($args[1])){
							$sender->sendMessage($player->getName() . " is already set to rank " . $args[1]);
							return false;
						}

						if(in_array(strtolower($args[1]), $this->getPlugin()->getAPI()->getAvaliableGroups())){
							$data["group"] = strtolower($args[1]);
							$this->getPlugin()->getAPI()->savePlayerData($player, $data);
							$sender->sendMessage("Successfully set " . $player->getName() . " to the group " . strtoupper($args[1]));
							$player->sendMessage("Your group has been set to " . strtoupper($args[1]));
						}else{
							$sender->sendMessage("Invalid group");
						}
					}
				}
			}
		}

		return true;
	}

}