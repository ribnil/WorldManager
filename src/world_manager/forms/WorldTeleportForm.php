<?php

declare(strict_types=1);

namespace world_manager\forms;

use pocketmine\Player;
use world_manager\WorldManager;
use world_manager\forms\template\ButtonForm;

class WorldTeleportForm extends ButtonForm
{
	public function __construct(WorldManager $worldManager) {
		$handler = $worldManager->getWorldHandler();
		$worlds = $handler->getAllWorldName();
		parent::__construct(function(Player $player, $data) use($handler, $worlds): void {
			if($data !== null)
				$handler->shiftWorld($player, $worlds[$data]);
		}, "World Teleporter.", "warp to...", $worlds);
	}
}