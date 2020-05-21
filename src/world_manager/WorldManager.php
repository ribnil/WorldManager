<?php

declare(strict_types=1);

namespace world_manager;

use pocketmine\plugin\PluginBase;
use world_manager\command\WorldBackup;
use world_manager\command\WorldManagement;
use function date_default_timezone_set;

class WorldManager extends PluginBase
{
	/** @var WorldHandler */
	private $worldHandler;

	public function onLoad(): void {
		date_default_timezone_set("Asia/Tokyo");
	}

	public function onEnable(): void {
		$this->worldHandler = new WorldHandler($this);
		$this->getServer()->getCommandMap()->registerAll("pocketmine", [
			new WorldBackup(
				$this,
				"backup",
				"world backup command.",
				"/backupping, /wb",
				["wb"]
			),
			new WorldManagement(
				$this->getWorldHandler(),
				"management",
				"send world management form.",
				"/management, /world",
				["world"]
			)
		]);
	}

	/** @return WorldHandler */
	public function getWorldHandler(): WorldHandler {
		return $this->worldHandler;
	}
}