<?php

declare(strict_types=1);

namespace world_manager\forms;

use pocketmine\form\Form;
use pocketmine\Player;
use pocketmine\Server;
use world_manager\forms\template\ButtonForm;
use world_manager\WorldHandler;

class WorldGenerateForm extends ButtonForm
{
	/**
	 * WorldGenerateForm constructor.
	 * @param WorldHandler $worldHandler
	 */
	public function __construct(WorldHandler $worldHandler) {
		parent::__construct(function(Player $player, $data) use($worldHandler): void {
			if($data === null) $player->sendForm(new ManagementForm($worldHandler));
			else $player->sendForm(new CustomWorldForm($worldHandler, $type = $data === 0 ? WorldHandler::FLAT : WorldHandler::DEFAULT));
		}, "World Generate Form.", "", [
			"flat",
			"default"
		]);
	}
}

class CustomWorldForm implements Form
{
	/** @var WorldHandler */
	private $worldHandler;

	/** @var string */
	private $worldType;

	/**
	 * CustomWorldForm constructor.
	 * @param WorldHandler $worldHandler
	 * @param string $worldType
	 */
	public function __construct(WorldHandler $worldHandler, string $worldType) {
		$this->worldHandler = $worldHandler;
		$this->worldType = $worldType;
	}

	/** @return WorldHandler */
	private function getWorldHandler(): WorldHandler {
		return $this->worldHandler;
	}

	/** @return string */
	private function getWorldType(): string {
		return $this->worldType;
	}

	public function handleResponse(Player $player, $data): void {
		if($data === null) {
			$player->sendForm(new WorldGenerateForm($this->getWorldHandler()));
		} else {
			if($this->getWorldHandler()->generate($this->getWorldType(), $data[0], ["preset" => $data[1]]))
				Server::getInstance()->broadcastMessage("§a[WorldManager] SUCCESS GENERATE WORLD: ".$data[0]);
			else
				Server::getInstance()->broadcastMessage("§e[WorldManager] MISSING GENERATE WORLD: ".$data[0]);
		}
	}

	public function jsonSerialize(): array {
		return [
			"type" => "custom_form",
			"title" => "World Customize.",
			"content" => [
				[
					"type" => "input",
					"text" => "World Name.",
					"placeholder" => "set world name in here.",
					"default" => ""
				],
				[
					"type" => "input",
					"text" => "World Preset.",
					"placeholder" => "set world preset in here.",
					"default" => WorldHandler::FLAT_DEFAULT_PRESET
				]
			]
		];
	}
}