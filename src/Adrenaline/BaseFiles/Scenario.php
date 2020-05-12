<?php
declare(strict_types=1);

namespace Adrenaline\BaseFiles;

use Adrenaline\Loader;

class Scenario{

	/** @var Loader */
	private $loader;

	/**@var bool $active */
	private $active;

	/** @var string $name */
	private $name;

	/** @var bool $isDefault */
	private $isDefault;

	/** @var string[] $aliases */
	private $aliases;

	/**
	 * Scenario constructor.
	 *
	 * @param Loader $loader
	 * @param string $name
	 * @param array  $aliases
	 * @param bool   $isDefault
	 */
	public function __construct(Loader $loader, string $name, array $aliases, bool $isDefault = false){
		$this->loader = $loader;
		$this->name = $name;
		$this->aliases = $aliases;
		$this->isDefault = $isDefault;
		if($isDefault){
			$this->active = true;
		}else{
			$this->active = false;
		}
	}

	/**
	 * @return Loader
	 */
	public function getLoader() : Loader{
		return $this->loader;
	}

	/**
	 * @return bool
	 */
	public function isActive() : bool{
		return $this->active;
	}

	/**
	 * @param bool $value
	 */
	public function setActive(bool $value){
		$this->active = $value;
	}

	/**
	 * @param string $string
	 *
	 * @return bool
	 */
	public function stringMatches(string $string) : bool{
		$string = strtolower($string);
		foreach($this->getAliases() as $alias){
			if(($string == strtolower($this->getName())) or ($string == strtolower($alias))){
				return true;
			}
		}

		return false;
	}

	/**
	 * @return string[]
	 */
	public function getAliases() : array{
		return $this->aliases;
	}

	/**
	 * @param string[] $aliases
	 */
	public function setAliases(array $aliases){
		$this->aliases = $aliases;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return $this->name;
	}

}