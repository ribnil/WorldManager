<?php

declare(strict_types=1);

use pocketmine\plugin\PluginBase;
use function date_default_timezone_set;

class WorldManager extends PluginBase
{
	public function onEnable(): void {
		date_default_timezone_set("Asia/Tokyo");
	}

	public function onDisable(): void {
	}
}