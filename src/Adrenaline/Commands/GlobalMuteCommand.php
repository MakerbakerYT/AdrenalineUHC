<?php

declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class GlobalMuteCommand extends BaseCommand{
	/**
	 * GlobalMuteCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "gmute", "Enable/disable globalmute", "/gmute [on|off]");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if($sender instanceof Player){
			if(in_array($this->getPlugin()->getAPI()->getGroup($sender), ["mod", "owner"]) or $sender->isOp()){
				if(count($args) < 1){
					$sender->sendMessage($this->getUsage());

					return false;
				}

				if(isset($args[0])){
					switch($args[0]){
						case "off":
							$this->getPlugin()->getAPI()->setGlobalMute(false);
							$this->getPlugin()->getServer()->broadcastMessage($this->getPlugin()->getAPI()->getPrefix() . "GlobalMute disabled!");
							break;

						case "on":
							$this->getPlugin()->getAPI()->setGlobalMute(true);
							$this->getPlugin()->getServer()->broadcastMessage($this->getPlugin()->getAPI()->getPrefix() . "GlobalMute enabled!");
							break;
					}
				}
			}
		}

		return false;
	}
}