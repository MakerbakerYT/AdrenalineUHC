<?php
declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\Player;

class SpectateCommand extends BaseCommand{
	/**
	 * SpectateCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "spectate", "Spectate a player!", "/spectate [player]");
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
				if($sender->isSpectator()){
					$player = $sender->getServer()->getPlayer($args[0]);
					if($player === null){
						$sender->sendMessage("Player not found");
					}else{
						$sender->teleport(new Vector3($player->x, $player->y, $player->z));
					}
				}else{
					$sender->sendMessage("Only spectators can watch others!");
				}
			}
		}
	}
}