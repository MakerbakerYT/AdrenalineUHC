<?php
declare(strict_types=1);

namespace Adrenaline\Managers;

use Adrenaline\BaseFiles\Scenario;
use Adrenaline\Loader;
use Adrenaline\Scenarios\BloodDiamond;
use Adrenaline\Scenarios\CutClean;
use Adrenaline\Scenarios\Diamondless;
use Adrenaline\Scenarios\NoFall;

class ScenarioManager{

	/** @var Scenario[] */
	public $scenarios = [];
	private $plugin;

	/**
	 * ScenarioManager constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		$this->plugin = $plugin;
		$this->setScenarios();
	}

	private function setScenarios(){
		$this->addScenario(new CutClean($this->plugin));
		$this->addScenario(new Diamondless($this->plugin));
		$this->addScenario(new BloodDiamond($this->plugin));
		$this->addScenario(new NoFall($this->plugin));
	}

	/**
	 * @param Scenario $scenario
	 */
	public function addScenario(Scenario $scenario){
		$this->scenarios[$scenario->getName()] = $scenario;
	}

	/**
	 * @return bool
	 */
	public function scenariosEmpty(){
		foreach($this->getScenarios() as $scenario){
			if($scenario->isActive()){
				return false;
			}
		}

		return true;
	}

	/**
	 * @return Scenario[]
	 */
	public function getScenarios(){
		return $this->scenarios;
	}

	/**
	 * @return bool
	 */
	public function scenariosFull(){
		foreach($this->getScenarios() as $scenario){
			if(!$scenario->isActive()){
				return false;
			}
		}

		return true;
	}

	/**
	 * @param string $name
	 *
	 * @return Scenario|null
	 */
	public function getScenarioByName(string $name){
		$name = strtolower($name);
		foreach($this->scenarios as $scenario){
			if(strtolower($scenario->getName()) === $name){
				return $scenario;
			}
		}

		return null;
	}

}