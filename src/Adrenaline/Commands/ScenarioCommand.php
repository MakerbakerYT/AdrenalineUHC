<?php
declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ScenarioCommand extends BaseCommand{
	/**
	 * ScenarioCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "scenario", "Set and remove scenarios", "/scenario [set|list|rem] [scenario]");
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
		if(!$this->getPlugin()->getAPI()->isCommandDisabled($this->getName())){
			if(isset($args[0])){
				if(isset($args[1])){
					if($sender instanceof Player){
						if(in_array($this->getPlugin()->getAPI()->getGroup($sender), ["mod", "owner"]) or $sender->isOp()){
							switch($args[0]){
								case "set":
									foreach($this->getPlugin()->getAPI()->getScenarioManager()->getScenarios() as $scenario){
										if($scenario->stringMatches($args[1])){
											if(!$scenario->isActive()){
												$sender->sendMessage($this->getPlugin()->getAPI()->getPrefix() . $scenario->getName() . " has been enabled!");
												$scenario->setActive(true);

												return true;
											}else{
												$sender->sendMessage($this->getPlugin()->getAPI()->getPrefix() . TextFormat::RED . $scenario->getName() . " is already enabled!");

												return false;
											}
										}
									}
									break;
								case "rem":
									foreach($this->getPlugin()->getAPI()->getScenarioManager()->getScenarios() as $scenario){
										if($scenario->stringMatches($args[1])){
											if($scenario->isActive()){
												$sender->sendMessage($this->getPlugin()->getAPI()->getPrefix() . $scenario->getName() . " has been disabled!");
												$scenario->setActive(false);

												return true;
											}else{
												$sender->sendMessage($this->getPlugin()->getAPI()->getPrefix() . TextFormat::RED . $scenario->getName() . " is already disabled!");

												return false;
											}
										}
									}
									break;
							}
						}
					}
				}else{
					switch($args[0]){
						case "list":
							foreach($this->getPlugin()->getAPI()->getScenarioManager()->getScenarios() as $scenario){
								$active = $scenario->isActive() ? TextFormat::AQUA . "ENABLED" : TextFormat::RED . "DISABLED";
								$sender->sendMessage(TextFormat::GOLD . "[" . $scenario->getName() . "] " . $active);
							}
					}
				}
			}
		}

		return false;
	}

}