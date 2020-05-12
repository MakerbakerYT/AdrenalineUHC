<?php
declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\Player;

class TpallCommand extends BaseCommand{
	/**
	 * TpallCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "tpall", "Teleport all players", "/tpall");
		$this->setPermission("adrenaline.command.tpall");
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
					foreach($this->getPlugin()->getServer()->getOnlinePlayers() as $p){
						$p->teleport(new Vector3($sender->x, $sender->y, $sender->z));
						$p->sendMessage("You have been teleported by " . $sender->getDisplayName());
					}
				}
			}
		}
	}
}