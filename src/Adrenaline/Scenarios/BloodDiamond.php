<?php
declare(strict_types=1);

namespace Adrenaline\Scenarios;

use Adrenaline\BaseFiles\Scenario;
use Adrenaline\Loader;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;

class BloodDiamond extends Scenario implements Listener{
	/**
	 * BloodDiamond constructor.
	 *
	 * @param Loader $plugin
	 *
	 * @internal param Loader $loader
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "BloodDiamond", ["bd"]);
		$this->getLoader()->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	/**
	 * @param BlockBreakEvent $event
	 *
	 * @return mixed|void
	 */
	public function onBreak(BlockBreakEvent $event){
		if($this->isActive()){
			if($event->getBlock()->getId() === Block::DIAMOND_ORE){
				$event->getPlayer()->attack(new EntityDamageEvent($event->getPlayer(), EntityDamageEvent::CAUSE_CUSTOM, 1));
			}
		}
	}
}