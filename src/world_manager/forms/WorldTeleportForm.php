<?php

declare(strict_types=1);

namespace world_manager\forms;

use pocketmine\Player;
use world_manager\WorldHandler;
use world_manager\forms\template\ButtonForm;

class WorldTeleportForm extends ButtonForm
{
	/**
	 * WorldTeleportForm constructor.
	 * @param WorldHandler $worldHandler
	 */
	public function __construct(WorldHandler $worldHandler) {
		$worlds = $worldHandler->getAllWorldName();
		parent::__construct(function(Player $player, $data) use($worldHandler, $worlds): void {
			if($data === null) $player->sendForm(new ManagementForm($worldHandler));
			else $worldHandler->shiftWorld($player, $worlds[$data]);
		}, "World Teleporter.", "warp to...", $worlds);
	}
}