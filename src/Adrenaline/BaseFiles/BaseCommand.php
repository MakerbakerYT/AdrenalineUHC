<?php
declare(strict_types=1);

namespace Adrenaline\BaseFiles;

use Adrenaline\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class BaseCommand extends Command implements PluginIdentifiableCommand{

	public $plugin;

	/**
	 * BaseCommand constructor.
	 *
	 * @param Loader      $plugin
	 * @param string      $name
	 * @param null|string $description
	 * @param string      $usageMessage
	 * @param array       $aliases
	 */
	public function __construct(Loader $plugin, string $name, string $description, string $usageMessage, array $aliases = []){
		parent::__construct($name, $description, $usageMessage, $aliases);
		$this->plugin = $plugin;
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if($this->testPermission($sender)){
			$result = $this->onExecute($sender, $args);
			if(is_string($result)){
				$sender->sendMessage($result);
			}

			return true;
		}

		return false;
	}

	/**
	 * @param CommandSender $sender
	 * @param array         $args
	 */
	public function onExecute(CommandSender $sender, array $args){
	}

	/**
	 * @return string
	 */
	public function getUsage() : string{
		return TextFormat::RED . "Usage: " . $this->usageMessage;
	}

	/**
	 * @return Loader|Plugin
	 */
	public function getPlugin() : Plugin{
		return $this->plugin;
	}
}