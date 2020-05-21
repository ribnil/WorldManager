<?php

declare(strict_types=1);

namespace world_manager\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use world_manager\forms\ManagementForm;
use world_manager\WorldHandler;

class WorldManagement extends Command
{
	/** @var WorldHandler */
	private $worldHandler;

	/**
	 * WorldManagement constructor.
	 * @param WorldHandler $worldHandler
	 * @param string $name
	 * @param string $description
	 * @param string|null $usageMessage
	 * @param array $aliases
	 */
	public function __construct(WorldHandler $worldHandler, string $name, string $description = "", string $usageMessage = null, array $aliases = []) {
		$this->worldHandler = $worldHandler;
		parent::__construct($name, $description, $usageMessage, $aliases);
	}

	/** @return WorldHandler */
	public function getWorldHandler(): WorldHandler {
		return $this->worldHandler;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
		if($sender instanceof Player && $sender->isOp())
			$sender->sendForm(new ManagementForm($this->getWorldHandler()));
		return true;
	}
}