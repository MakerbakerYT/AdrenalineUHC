<?php

declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class PvPCommand extends BaseCommand{
	/**
	 * PvPCommand constructor.
	 *
	 * @param Loader $loader
	 */
	public function __construct(Loader $loader){
		parent::__construct($loader, "pvp", "Enable and disable PvP", "/pvp [on|off]");
		$this->setPermission("adrenaline.command.pvp");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		parent::execute($sender, $commandLabel, $args);
		if($sender instanceof Player){
			if(in_array($this->getPlugin()->getAPI()->getGroup($sender), ["mod", "owner"]) or $sender->isOp()){
				if(isset($args[0])){
					switch($args[0]){
						case "on":
							$this->getPlugin()->getAPI()->setDifficulty(1);
							$this->getPlugin()->getServer()->broadcastMessage($this->getPlugin()->getAPI()->getPrefix() . "PvP has been enabled!");
							break;

						case "off":
							$this->getPlugin()->getAPI()->setDifficulty();
							$this->getPlugin()->getServer()->broadcastMessage($this->getPlugin()->getAPI()->getPrefix() . "PvP has been disabled!");
							break;
					}
				}else{
					$sender->sendMessage($this->getUsage());
				}
			}
		}

		return false;
	}

}