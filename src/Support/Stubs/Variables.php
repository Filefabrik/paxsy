<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Stubs;

use UnexpectedValueException;

/**
 * Handle Variables renderer
 */
class Variables
{
	/**
	 * @var array
	 */
	private array $replacementMap = [];

	/**
	 * @var array<string,class-string<VariablesRendererInterface>>
	 */
	private array $rendererClasses = [];

	/**
	 * @var array
	 */
	private array $variables = [];

	/**
	 * Render all give back an array with simple key value pairs to replace
	 *
	 * @return array<string,string>
	 */
	public function renderVariables(): array
	{
		$varsPool = [];
		foreach (array_keys($this->replacementMap) as $name) {
			$varsPool += [...$this->renderTypeVars($name)];
		}

		return $varsPool;
	}

	/**
	 * @param string $name
	 *
	 * @return array
	 */
	protected function renderTypeVars(string $name): array
	{
		$replacementMap = $this->replacementMap[$name] ?? null;
		$variables      = $this->variables[$name] ?? null;
		$rendererClass  = $this->getRendererClasses($name);

		return $replacementMap ? $rendererClass::toArray($replacementMap, $variables) : [];
	}

	/**
	 * @param string $name
	 *
	 * @return class-string<VariablesRendererInterface>
	 */
	protected function getRendererClasses(string $name): string
	{
		return $this->rendererClasses[$name] ?? throw new UnexpectedValueException('Renderer was not Registered');
	}

	/**
	 * From Config
	 *
	 * @param array $replacementMap
	 *
	 * @return $this
	 */
	public function setReplacementMaps(array $replacementMap): static
	{
		$this->replacementMap = $replacementMap;

		return $this;
	}

	public function setRendererClasses(array $rendererClasses): static
	{
		$this->rendererClasses = $rendererClasses;

		return $this;
	}

	/**
	 * @param string $name
	 * @param mixed  $variables
	 *
	 * @return $this
	 */
	public function addVariables(string $name, mixed $variables): Variables
	{
		$this->variables[$name] = $variables;

		return $this;
	}
}
