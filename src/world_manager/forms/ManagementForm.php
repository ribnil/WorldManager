<?php

declare(strict_types=1);

namespace world_manager\forms;

use pocketmine\Player;
use pocketmine\Server;
use world_manager\forms\template\ButtonForm;
use world_manager\WorldHandler;

class ManagementForm extends ButtonForm
{
	/**
	 * ManagementForm constructor.
	 * @param WorldHandler $worldHandler
	 */
	public function __construct(WorldHandler $worldHandler) {
		parent::__construct(function(Player $player, $data) use($worldHandler): void {
			if($data !== null) {
				switch($data) {
					case 0: $player->sendForm(new WorldTeleportForm($worldHandler)); break;
					case 1: break;
					case 2: Server::getInstance()->dispatchCommand($player, "backup"); break;
				}
			}
		}, "World Management.", "", [
			"World teleport.",
			"World generate.",
			"World backup | can use \"/backup\"."
		]);
	}
}