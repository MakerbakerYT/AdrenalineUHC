<?php
declare(strict_types=1);

namespace Adrenaline\Listener;

use Adrenaline\Loader;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\TextFormat;

class PlayerListener implements Listener{

	public $plugin;
	public $pos = [];

	/**
	 * PlayerListener constructor.
	 *
	 * @param Loader $loader
	 */
	public function __construct(Loader $loader){
		$this->plugin = $loader;
		$loader->getServer()->getPluginManager()->registerEvents($this, $loader);
	}

	/**
	 * @param EntityRegainHealthEvent $event
	 */
	public function onRegen(EntityRegainHealthEvent $event){
		if($event->getRegainReason() === EntityRegainHealthEvent::CAUSE_SATURATION){
			$event->setCancelled();
		}
	}

	/**
	 * @param PlayerPreLoginEvent $event
	 */
	public function onPreLogin(PlayerPreLoginEvent $event){
		$player = $event->getPlayer();
		$this->plugin->getAPI()->createPlayerData($player);
	}

	/**
	 * @param PlayerJoinEvent $event
	 */
	public function onJoin(PlayerJoinEvent $event){
		$event->setJoinMessage("");
		$player = $event->getPlayer();

		$player->setWhitelisted(true);
		if(isset($this->pos[$player->getName()])){
			$player->teleport($this->pos[$player->getName()]);
			unset($this->pos[$player->getName()]);
		}
	}

	/**
	 * @param PlayerChatEvent $event
	 */
	public function onChat(PlayerChatEvent $event){
		$player = $event->getPlayer();

		$event->setFormat((string) $this->plugin->getAPI()->getChatFormat($player, $event->getMessage()));

		if($this->plugin->getAPI()->getGlobalMute()){
			if(!$player->isOp()){
				$event->setCancelled();
				$player->sendMessage("You cannot chat right now!");
			}
		}
	}

	/**
	 * @param PlayerDeathEvent $event
	 */
	public function onDeath(PlayerDeathEvent $event){
		$player = $event->getPlayer();
		$player->setGamemode(3);
		$this->plugin->getServer()->broadcastMessage($this->plugin->getAPI()->getPrefix() . $player->getDisplayName() . " has been killed!");
		$player->addTitle(TextFormat::RED . "You died!", TextFormat::GOLD . "Do /spectate to spectate a player!");
		$player->setWhitelisted(false);
	}

	/**
	 * @param PlayerQuitEvent $event
	 */
	public function onQuit(PlayerQuitEvent $event){
		$player = $event->getPlayer();

		if(!isset($this->pos[$player->getName()])){
			$this->pos[$player->getName()] = clone $player->getLevel()->getSafeSpawn($player->getPosition());
		}
	}
}