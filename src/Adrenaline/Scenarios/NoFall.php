<?php
declare(strict_types=1);

namespace Adrenaline\Scenarios;

use Adrenaline\BaseFiles\Scenario;
use Adrenaline\Loader;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;

class NoFall extends Scenario implements Listener{
	/**
	 * NoFall constructor.
	 *
	 * @param Loader $plugin
	 *
	 * @internal param Loader $loader
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "NoFall", ["nf"]);
		$this->getLoader()->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	/**
	 * @param EntityDamageEvent $event
	 */
	public function onEntityDamage(EntityDamageEvent $event){
		if($this->isActive()){
			if($event->getCause() === EntityDamageEvent::CAUSE_FALL){
				$event->setCancelled();
			}
		}
	}
}