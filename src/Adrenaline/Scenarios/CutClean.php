<?php

declare(strict_types=1);

namespace Adrenaline\Scenarios;

use Adrenaline\BaseFiles\Scenario;
use Adrenaline\Loader;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;

class CutClean extends Scenario implements Listener{

	public $plugin;

	/**
	 * CutClean constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "CutClean", ["cc"]);
		$this->getLoader()->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	/**
	 * @param BlockBreakEvent $event
	 *
	 * @return mixed|void
	 */
	public function onBreak(BlockBreakEvent $event){
		if($this->isActive()){
			switch($event->getBlock()->getId()){
				case Block::IRON_ORE:
					$event->setDrops([Item::get(Item::IRON_INGOT)]);
					break;
				case Block::GOLD_ORE:
					$event->setDrops([Item::get(Item::GOLD_INGOT)]);
					break;
			}
		}
	}
}