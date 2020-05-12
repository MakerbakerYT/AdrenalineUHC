<?php

declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class HostCommand extends BaseCommand{
	/**
	 * HostCommand constructor.
	 *
	 * @param Loader $loader
	 */
	public function __construct(Loader $loader){
		parent::__construct($loader, "host", "Host command!", "/host");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool|void
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if(!$this->getPlugin()->getAPI()->isCommandDisabled($this->getName())){
			if($sender instanceof Player){
				if(in_array($this->getPlugin()->getAPI()->getGroup($sender), ["mod", "owner"]) or $sender->isOp()){
					if(isset($args[0])){
						switch($args[0]){
							case "start":
								if(!$this->plugin->getAPI()->isUsed()){
									$this->getPlugin()->getAPI()->callTimer();
									$this->getPlugin()->getAPI()->setUsed(true);
								}else{
									$sender->sendMessage("Task is already running!");
								}
								break;
						}
					}
				}
			}
		}
	}

}