<?php

declare(strict_types=1);

namespace Adrenaline\Tasks;

use Adrenaline\Loader;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\scheduler\Task;

class TimerTask extends Task{

	public $plugin;
	public $timer = 6; //Value: 6
	public $grace = 1201; //Value: 1201
	public $pvp = 1201; // Value: 1201
	public $border = 2000; //Value: 2000
	public $shrink = 1201; //Value: 1201 TODO

	/**
	 * TimerTask constructor.
	 *
	 * @param Loader $owner
	 */
	public function __construct(Loader $owner){
		parent::__construct($owner);
		$this->plugin = $owner;
	}

	/**
	 * @param $int
	 *
	 * @return string
	 */
	public function seconds2string(int $int) : string{
		$m = floor($int / 60);
		$s = floor($int % 60);

		return (($m < 10 ? "0" : "") . $m . ":" . ($s < 10 ? "0" : "") . $s);
	}

	/**
	 * @param $currentTick
	 */
	public function onRun($currentTick){
		$this->callTitle();
		$this->warpInBorder();
		$this->plugin->getAPI()->sendBossBar();
	}

	public function callTitle(){
		$this->timer--;
		foreach($this->plugin->getServer()->getOnlinePlayers() as $p){
			if($this->timer === 0){
				$p->addTitle("Started");
				$p->getLevel()->broadcastLevelEvent($p, 2006);
				//$p->getLevel()->broadcastLevelEvent($p, 3005, 0);
			}elseif($this->timer > 0){
				$p->addTitle((string) $this->timer);
				$p->getLevel()->broadcastLevelEvent($p, LevelEventPacket::EVENT_SOUND_ANVIL_FALL);
				//$p->getLevel()->broadcastLevelEvent($p, 3005, 1);
			}elseif($this->timer <= 0){
				//$this->callGrace();
			}
		}
	}

	public function callGrace(){
		$this->grace--;
		if($this->grace <= 11){
			if($this->grace >= 0){
				$this->setMessage("grace", $this->grace);
			}elseif($this->grace <= 0){
				$this->callPvP();
			}
		}
	}

	/**
	 * @param string $eventName
	 * @param        $timer
	 */
	public function setMessage(string $eventName, $timer){
		foreach($this->plugin->getServer()->getOnlinePlayers() as $p){
			switch($eventName){
				case "grace":
					$p->sendTip("Grace will end in " . $timer);
					break;
				case "pvp":
					$p->sendTip("Everything is normal for " . $timer);
					break;
				default:
					null;
			}
		}
	}

	public function callPvP(){
		$this->pvp--;
		if($this->pvp <= 11){
			if($this->pvp >= 0){
				$this->setMessage("pvp", $this->pvp);
			}elseif($this->pvp === 0){
				$this->plugin->getAPI()->setDifficulty(1);
				$this->plugin->getServer()->broadcastMessage("PvP enabled!");
			}/*elseif($this->pvp <= 0){
			$this->callEvent("bordershrink");
			}*/
		}
	}

	/**
	 * @return bool
	 */
	public function warpInBorder() : bool{
		$level = $this->plugin->getServer()->getDefaultLevel();
		foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
			if(($player->getFloorX() >= $this->border or $player->getFloorX() <= -$this->border or
					$player->getFloorZ() >= $this->border or $player->getFloorZ() <= -$this->border) && $player->getGamemode() == 0
			){
				$x = mt_rand(-$this->border, $this->border);
				$z = mt_rand(-$this->border, $this->border);
				if(!$level->isChunkLoaded($x, $z)){
					$level->loadChunk($x << 4, $z << 4);
				}
				$player->teleport(new Position($x, $level->getHighestBlockAt($x, $z) + 1, $z, $level));
				$player->sendMessage("Teleported you inside the border.");
			}
		}

		return false;
	}

	public function callBorderShrink(){
		if($this->shrink === 0){
		}
	}

	public function cancelTask(){
		$this->plugin->getScheduler()->cancelTask($this->getTaskId());
	}

	/**
	 * @param $value
	 */
	public function setBorder(int $value){
		$this->border = $value;
	}
}