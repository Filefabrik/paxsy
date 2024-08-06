<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Helper;

trait TraitGenericLines
{
	/**
	 * @var array
	 */
	private array $lines = [];

	/**
	 * @param $content
	 *
	 * @return $this
	 */
	protected function line($content): static
	{
		$this->lines[] = $content;

		return $this;
	}

	public function getLines(): array
	{
		return $this->lines;
	}

	public function mapLinesInto($lineMethod): void
	{
		array_map(fn($line) => $lineMethod->line($line), $this->getLines());
	}
}
