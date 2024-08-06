<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support;

use Filefabrik\Bootraiser\Support\Str\Pathering;
use Illuminate\Support\Str;
use UnexpectedValueException;

/**
 * @phpstan-type StringularityArray array{classSegment:string,nameSegment:string,nameSingular:string,namePlural:string}
 */
class Stringularity
{
	/**
	 * Must set a minimum one of them. Another Part will calculate
	 *
	 * @param string|null $name      my-name-space
	 * @param string|null $className MyNameSpace
	 */
	public function __construct(private ?string $name = null, private ?string $className = null)
	{
		if (null === $this->name && null === $this->className) {
			throw new UnexpectedValueException('Name and ClassName name cannot be null. Set name or/and className.');
		}

		! $this->name ?: $this->name           = Str::kebab($this->removeUnwanted($this->name));
		! $this->className ?: $this->className = Str::studly($this->removeUnwanted($this->className));
	}

	/**
	 * todo make other input filters because of accidental creating bullshit names
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	private function removeUnwanted(string $string): string
	{
		return trim(Pathering::trim($string), ' ');
	}

	/**
	 * MyNamespaceSegment
	 *
	 * @return string
	 */
	public function toClass(): string
	{
		return $this->className ?? Str::studly($this->name);
	}

	/**
	 * my-namespace-segment
	 *
	 * @return string
	 */
	public function toName(): string
	{
		return $this->name ?? Str::kebab($this->className);
	}

	/**
	 * my-namespace-segment
	 *
	 * @return string
	 */
	public function toSingularName(): string
	{
		return Str::singular($this->toName());
	}

	/**
	 * my-namespace-segments
	 *
	 * @return string
	 */
	public function toPluralName(): string
	{
		return Str::plural($this->toName());
	}
}
