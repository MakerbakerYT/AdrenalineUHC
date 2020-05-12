<?php
declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class GroupsCommand extends BaseCommand{
	/**
	 * GroupsCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "groups", "View avaliable groups", "/groups", []);
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		$sender->sendMessage(TextFormat::RED . TextFormat::BOLD . "Groups:");
		foreach($this->getPlugin()->getAPI()->getAvaliableGroups() as $group){
			$sender->sendMessage(TextFormat::GOLD . strtoupper($group) . "\n");
		}
	}
}