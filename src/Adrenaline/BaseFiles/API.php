<?php
declare(strict_types=1);

namespace Adrenaline\BaseFiles;

use Adrenaline\Listener\PlayerListener;
use Adrenaline\Loader;
use Adrenaline\Managers\CommandManager;
use Adrenaline\Managers\ScenarioManager;
use Adrenaline\Tasks\TimerTask;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\BossEventPacket;
use pocketmine\network\mcpe\protocol\SetDifficultyPacket;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\utils\UUID;

class API{

	private $config, $chatconfig;
	private $gmute = false;
	private $used = false;
	/** @var Loader */
	private $loader;
	/** @var TimerTask */
	private $timer;
	/** @var ScenarioManager */
	private $scenarioManager;
	/** @var PlayerListener */
	private $playerListener;
	/** @var CommandManager */
	private $commandManager;

	/**
	 * API constructor.
	 *
	 * @param Loader $loader
	 * @since 1.0.0 Beta 1
	 *
	 */
	public function __construct(Loader $loader){
		$this->loader = $loader;
		$this->init();
	}

	/**
	 * @since 1.0.0 Beta 1
	 */
	public function init(){
		$this->commandManager = new CommandManager($this->getLoader());
		$this->playerListener = new PlayerListener($this->getLoader());
		$this->scenarioManager = new ScenarioManager($this->getLoader());
		$this->timer = new TimerTask($this->getLoader());
		$this->config = new Config($this->getLoader()->getDataFolder() . "config.json", Config::JSON);
		$this->chatconfig = new Config($this->getLoader()->getDataFolder() . "chat.json", Config::JSON);
	}

	/**
	 * @return Loader
	 * @since 1.0.0 Beta 1
	 *
	 */
	private function getLoader() : Loader{
		return $this->loader;
	}

	/**
	 * @return CommandManager
	 * @since 1.0.0 Beta 2
	 *
	 */
	public function getCommandManager() : CommandManager{
		return $this->commandManager;
	}

	/**
	 * @return PlayerListener
	 * @since 1.0.0 Beta 1
	 *
	 */
	public function getPlayerListener() : PlayerListener{
		return $this->playerListener;
	}

	/**
	 * @return ScenarioManager
	 * @since 1.0.0 Beta 4
	 *
	 */
	public function getScenarioManager() : ScenarioManager{
		return $this->scenarioManager;
	}

	/**
	 * @return TimerTask
	 * @since 1.0.0 Beta 3
	 *
	 */
	public function getTimer() : TimerTask{
		return $this->timer;
	}

	/**
	 * @return string
	 * @since 1.0.0 Beta 1
	 *
	 */
	public function getPrefix() : string{
		return TextFormat::BOLD . TextFormat::RED . "Adrenaline> " . TextFormat::RESET . TextFormat::GOLD;
	}

	/**
	 * @param $resource
	 * @param $value
	 */
	public function setInMainConfig($resource, $value){
		//TODO
	}

	/**
	 * @param $command
	 *
	 * @return mixed
	 * @since 1.0.1
	 *
	 */
	public function isCommandDisabled($command){
		return $this->getMainConfig()->get("commands")[$command];
	}

	/**
	 * @return Config
	 * @since 1.0.1
	 *
	 */
	public function getMainConfig() : Config{
		return $this->config;
	}

	/**
	 * @return int
	 * @since 1.0.0 Beta 3
	 *
	 */
	public function callTimer() : int{
		$main = new TimerTask($this->getLoader());

		return $this->getLoader()->getScheduler()->scheduleRepeatingTask($main, 20)->getTaskId();
	}

	/**
	 * @return bool
	 * @since 1.0.0 Beta 2
	 *
	 */
	public function isUsed() : bool{
		return $this->used;
	}

	/**
	 * @param bool $used
	 * @since 1.0.0 Beta 2
	 *
	 */
	public function setUsed(bool $used = false){
		$this->used = $used;
	}

	/**
	 * @return bool
	 * @since 1.0.0 Beta 2
	 *
	 */
	public function getGlobalMute() : bool{
		return $this->gmute;
	}

	/**
	 * @param bool $value
	 * @since 1.0.0 Beta 2
	 *
	 */
	public function setGlobalMute(bool $value = false){
		$this->gmute = $value;
	}

	/**
	 * @param int $difficulty
	 * @since 1.0.0 Beta 4
	 *
	 */
	public function setDifficulty(int $difficulty = 0){
		$this->getLoader()->getServer()->setConfigInt("difficulty", $difficulty);
		$pk = new SetDifficultyPacket();
		$pk->difficulty = $difficulty;
		$this->getLoader()->getServer()->broadcastPacket($this->getLoader()->getServer()->getOnlinePlayers(), $pk);
	}

	/**
	 * @return array
	 * @since 1.0.1
	 *
	 */
	public function getAvaliableGroups() : array{
		return ["default", "mod", "owner", "famous", "famous+", "legend", "legend+"];
	}

	/**
	 * @param Player $player
	 * @param string $message
	 *
	 * @return bool|mixed
	 * @since 1.0.1
	 *
	 */
	public function getChatFormat(Player $player, string $message){
		$group = $this->getGroup($player);
		$format = $this->getChatConfig()->get($group);
		$format = $format['format'];
		$format = str_replace("{name}", $player->getName(), $format);
		$format = str_replace("{message}", $message, $format);

		return $format;
	}

	/**
	 * @param Player $player
	 *
	 * @return bool|mixed
	 * @since 1.0.1
	 *
	 */
	public function getGroup(Player $player){
		$name = trim(strtolower($player->getName()));
		$data = new Config($this->getLoader()->getDataFolder() . "players/$name.yml", Config::YAML);

		$group = $data->get("group");

		return $group;
	}

	/**
	 * @return Config
	 * @since 1.0.1
	 *
	 */
	public function getChatConfig() : Config{
		return $this->chatconfig;
	}

	/**
	 * @param Player $player
	 * @param array  $config
	 */
	public function savePlayerData(Player $player, array $config){
		$name = trim(strtolower($player->getName()));
		$data = new Config($this->getLoader()->getDataFolder() . "players/$name.yml", Config::YAML);
		$data->setAll($config);
		$data->save();
	}

	/**
	 * @param Player $player
	 *
	 * @return array
	 */
	public function createPlayerData(Player $player){
		$name = trim(strtolower($player->getName()));
		$path = $this->getLoader()->getDataFolder() . "players/$name.yml";
		if(!file_exists($path)){
			$data = new Config($this->getLoader()->getDataFolder() . "players/$name.yml", Config::YAML);
			$data->set("group", "default");
			$data->save();

			return $data->getAll();
		}else{
			return null;
		}
	}

	/**
	 * @param Player $player
	 *
	 * @return array|null
	 */
	public function getPlayerData(Player $player){
		$name = trim(strtolower($player->getName()));
		if($name === ""){
			return null;
		}
		$path = $this->getLoader()->getDataFolder() . "players/$name.yml";
		if(!file_exists($path)){
			return null;
		}else{
			$config = new Config($path, Config::YAML);

			return $config->getAll();
		}
	}

	/**
	 * @since 1.0.1
	 */
	//TODO: Make this look better, and cleanup.

	public function sendBossBar(){
		foreach($this->getLoader()->getServer()->getOnlinePlayers() as $p){
			$flags = 1 << Entity::DATA_FLAG_INVISIBLE;
			$flags |= 0 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
			$flags |= 0 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG;
			$flags |= 1 << Entity::DATA_FLAG_IMMOBILE;

			$pk = new BossEventPacket();
			$pk->title = "\n\n    Adrenaline\nX: " . round($p->getX()) . " Y: " . round($p->getY()) . " Z: " . round($p->getZ());
			$pk->healthPercent = 100;
			$pk->playerEid = $p->getId();
			$pk->bossEid = 8385757857;
			$pk->unknownShort = 0;
			$pk->color = 0;
			$pk->overlay = 0;
			$this->getLoader()->getServer()->broadcastPacket($this->getLoader()->getServer()->getOnlinePlayers(), $pk);

			$spawn4 = new AddPlayerPacket();
			$spawn4->entityRuntimeId = 8385757857;
			$spawn4->uuid = UUID::fromRandom();
			$spawn4->username = '';
			$spawn4->motion = null;
			$spawn4->position = new Vector3($p->getX(), $p->getY() - 10, $p->getZ());
			$spawn4->item = Item::get(Item::AIR);
			$spawn4->metadata = [
				Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
				Entity::DATA_LEAD_HOLDER_EID => [Entity::DATA_TYPE_LONG, -1],
			];
			$p->dataPacket($spawn4);
		}
	}
}