<?php

declare(strict_types=1);

namespace world_manager;

use pocketmine\plugin\PluginBase;
use world_manager\command\WorldBackup;
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
		$this->getServer()->getCommandMap()->register("pocketmine", new WorldBackup(
			$this, "backup", "world backup command.", "/backup", ["wb"]
		));
	}

	/** @return WorldHandler */
	public function getWorldHandler(): WorldHandler {
		return $this->worldHandler;
	}
}