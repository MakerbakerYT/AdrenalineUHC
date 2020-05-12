<?php

declare(strict_types=1);

namespace Adrenaline;

use Adrenaline\BaseFiles\API;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase{

	private $api;

	public function onEnable(){
		if(!file_exists($this->getDataFolder())){
			mkdir($this->getDataFolder());
		}elseif(!file_exists($this->getDataFolder() . "players/")){
			mkdir($this->getDataFolder() . "players/");
		}

		$this->saveResource("config.json");
		$this->saveResource("chat.json");

		$this->api = new API($this);

		if($this->getAPI()->getMainConfig()->get("settings")["disabled"]){
			$this->getLogger()->info("Plugin disabled, due to settings.disabled");
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
	}

	/**
	 * @return API
	 */
	public function getAPI() : API{
		return $this->api;
	}
}