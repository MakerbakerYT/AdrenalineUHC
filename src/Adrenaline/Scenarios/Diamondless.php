<?php
declare(strict_types=1);

namespace Adrenaline\Scenarios;

use Adrenaline\BaseFiles\Scenario;
use Adrenaline\Loader;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class Diamondless extends Scenario implements Listener{
	/**
	 * Diamondless constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "Diamondless", ["dl"]);
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
				$event->setDrops([]);
			}
		}
	}
}