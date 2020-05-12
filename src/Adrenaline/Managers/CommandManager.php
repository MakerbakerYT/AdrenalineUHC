<?php

declare(strict_types=1);

namespace Adrenaline\Managers;

use Adrenaline\Commands\GlobalMuteCommand;
use Adrenaline\Commands\GroupsCommand;
use Adrenaline\Commands\HostCommand;
use Adrenaline\Commands\MeetupCommand;
use Adrenaline\Commands\PvPCommand;
use Adrenaline\Commands\ScenarioCommand;
use Adrenaline\Commands\SetGroupCommand;
use Adrenaline\Commands\SpectateCommand;
use Adrenaline\Commands\TpallCommand;
use Adrenaline\Commands\WorldCommand;
use Adrenaline\Loader;

class CommandManager{

	private $plugin;

	/**
	 * CommandManager constructor.
	 *
	 * @param Loader $loader
	 */
	public function __construct(Loader $loader){
		$this->plugin = $loader;
		$this->registerCommands();
	}

	public function registerCommands(){
		$map = $this->plugin->getServer()->getCommandMap();

		$map->registerAll(
			"uhc", [
			new HostCommand($this->plugin),
			new PvPCommand($this->plugin),
			new GlobalMuteCommand($this->plugin),
			new ScenarioCommand($this->plugin),
			new TpallCommand($this->plugin),
			new SpectateCommand($this->plugin),
			new WorldCommand($this->plugin),
			new SetGroupCommand($this->plugin),
			new MeetupCommand($this->plugin),
			new GroupsCommand($this->plugin)
		]);
	}
}