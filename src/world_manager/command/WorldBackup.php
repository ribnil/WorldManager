<?php

declare(strict_types=1);

namespace ricca\commands\server;

use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\scheduler\AsyncTask;
use world_manager\WorldManager;
use ZipArchive;
use function date;
use function glob;
use function mkdir;
use function file_exists;
use function str_replace;

class WorldBackup extends Command
{
	/** @var WorldManager */
	private $worldManager;

	public function __construct(WorldManager $worldManager, string $name, string $description = "", string $usageMessage = null, array $aliases = []) {
		$this->worldManager = $worldManager;
		parent::__construct($name, $description, $usageMessage, $aliases);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
		if($sender instanceof ConsoleCommandSender || $sender instanceof Player && $sender->isOp())
			$this->getWorldManager()->getServer()->getAsyncPool()->submitTask(new BackupTask(
				$this->getWorldManager()->getWorldHandler()->getAllWorldName(),
				$this->getWorldManager()->getDataFolder(),
				date("Ymd_His"))
			);
		return true;
	}

	/** @return WorldManager */
	public function getWorldManager(): WorldManager {
		return $this->worldManager;
	}
}

class BackupTask extends AsyncTask
{
	/** @var array */
	private $allWorld;

	/** @var string */
	private $folderPath;

	/** @var string */
	private $currentTime;

	/**
	 * Backup constructor.
	 * @param array $allWorld
	 * @param string $folderPath
	 * @param string $currentTime
	 */
	public function __construct(array $allWorld, string $folderPath, string $currentTime) {
		$this->allWorld = $allWorld;
		$this->folderPath = $folderPath;
		$this->currentTime = $currentTime;
	}

	/** @return array */
	private function getAllWorld(): array {
		return $this->allWorld;
	}

	/** @return string */
	private function getFolderPath(): string {
		return $this->folderPath;
	}

	/** @return string */
	private function getCurrentTime(): string {
		return $this->currentTime;
	}

	private function backup(string $folderPath, string $currentTime): void {
		$backupPath = $folderPath."world_backup/";
		if(!file_exists($backupPath))
			mkdir($backupPath);

		$zip = new ZipArchive();
		$zip->open($backupPath = $backupPath.$currentTime.".zip", ZipArchive::CREATE);

		foreach($this->getAllWorld() as $worldName) {
			$zip->addFile("./worlds/".$worldName."/level.dat");
			foreach(glob("./worlds/".$worldName."/region/*") as $worldDirectoryPath) {
				$content = str_replace("./worlds/".$worldName."/region/", '', $worldDirectoryPath);
				$zip->addFile("./worlds/".$worldName."/region/".$content);
			}
		}

		$zip->close();
	}

	public function onRun() {
		echo "backup world...".PHP_EOL;
		$this->backup($this->getFolderPath(), $this->getCurrentTime());
		echo "complete backup world directory.".PHP_EOL;
	}
}