<?php
declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class WorldCommand extends BaseCommand{
	/**
	 * WorldCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "world", "Teleport to a world", "/world [worldname]");
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
					$world = $sender->getServer()->getLevelByName($args[0]);
					if(!$sender->getServer()->isLevelLoaded((string) $world)){
						$sender->getServer()->loadLevel((string) $world);
						$sender->sendMessage("Loading!");
						$sender->teleport($world->getSafeSpawn());
						$sender->sendMessage("Teleported!");
					}else{
						$sender->teleport($world->getSafeSpawn());
						$sender->sendMessage("Teleported!");
					}
				}
			}
		}
	}
}