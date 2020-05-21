<?php

declare(strict_types=1);

namespace world_manager;

use pocketmine\Player;
use pocketmine\level\Level;
use pocketmine\level\generator\GeneratorManager;
use function time;
use function scandir;
use function array_diff;
use function array_values;

class WorldHandler
{
	public const FLAT = "flat";
	public const DEFAULT = "default";

	public const FLAT_DEFAULT_PRESET = "2;7,2x3,2;1;";

	/** @var array */
	private $worldsName;

	/** @var WorldManager */
	private $worldManager;

	/**
	 * WorldHandler constructor.
	 * @param WorldManager $worldManager
	 */
	public function __construct(WorldManager $worldManager) {
		$this->worldManager = $worldManager;
	}

	public function loadWorld(string $name): bool {
		return $this->getWorldManager()->getServer()->loadLevel($name);
	}

	public function getWorld(string $name): ?Level {
		return $this->getWorldManager()->getServer()->getLevelByName($name);
	}

	private function update(): void {
		$this->worldsName = array_values(array_diff(scandir("./worlds/"), [".", ".."]));
	}

	public function getAllWorldName(): array {
		$this->update();
		return $this->worldsName;
	}

	/**
	 * @param Player $player
	 * @param string $name
	 * @return void
	 */
	public function shiftWorld(Player $player, string $name): void {
		if(!$this->loadWorld($name)) {
			echo "not found world data: {$name}".PHP_EOL;
		} else {
			$level = $this->getWorld($name);
			$pos = $level->getSpawnLocation();
			$player->teleport($pos);
		}
	}

	/**
	 * @param string $type
	 * @param string $name
	 * @param array $options
	 * @return bool
	 */
	public function generate(string $type, string $name, array $options): bool {
		if($this->loadWorld($name))
			return false;
		return $this->getWorldManager()->getServer()->generateLevel(
			$name, time(), GeneratorManager::getGenerator($type), $options
		);
	}

	/** @return WorldManager */
	public function getWorldManager(): WorldManager {
		return $this->worldManager;
	}
}