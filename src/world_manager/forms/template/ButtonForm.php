<?php

declare(strict_types=1);

namespace world_manager\forms\template;

use Closure;
use pocketmine\form\Form;
use pocketmine\Player;

class ButtonForm implements Form
{
	/** @var Closure */
	private $closure;

	/** @var string */
	private $title;

	/** @var string */
	private $content;

	/** @var array */
	private $buttons;

	/**
	 * ButtonForm constructor.
	 * @param Closure $closure
	 * @param string $title
	 * @param string $content
	 * @param array $buttons
	 */
	public function __construct(Closure $closure, string $title = "", string $content = "", array $buttons = []) {
		$this->closure = $closure;
		$this->title = $title;
		$this->content = $content;
		$this->buttons = $buttons;
	}

	private function toArray(array $array): array {
		$result = [];
		foreach($array as $value) {
			$result[] = ['text' => $value];
		}

		return $result;
	}

	public function handleResponse(Player $player, $data): void {
		($this->closure)($player, $data);
	}

	public function jsonSerialize(): array {
		return [
			'type' => 'form',
			'title' => $this->title,
			'content' => $this->content,
			'buttons' => $this->toArray($this->buttons)
		];
	}
}