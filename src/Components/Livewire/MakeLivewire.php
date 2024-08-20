<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Components\Livewire;

use Illuminate\Support\Facades\File;
use Livewire\Features\SupportConsoleCommands\Commands\MakeCommand;
use Stringable;

if (class_exists(MakeCommand::class)) {
	/**
	 * todo move to src/Components
	 *
	 * @property ComponentParser $parser
	 */
	class MakeLivewire extends MakeCommand
	{
		use TraitLivewirePackagizer;

		protected $parser;

		public function handle(): void
		{
			// todo check why it is need to reset the ownPackage "cache" during construction
			$this->ownPackage = null;
			$this->package() ? $this->handle_package() : parent::handle();
		}

		public function getAliases(): array
		{
			return ['make:livewire', 'livewire:make'];
		}

		/**
		 * ugly copied the Livewire MakeCommand handle method to override the viewName()
		 */
		protected function parentHandle(): void
		{
			$name = $this->lineClassNameValid();
			if (! $name || ! $this->lineReservedClassName($name)) {
				return;
			}

			$this->stageCreateParts();
		}

		protected function stageOptions(): array
		{
			return [$this->option('force'), $this->option('inline'), $this->option('test') || $this->option('pest')];
		}

		protected function stageCreateParts(): void
		{
			[$force, $inline, $test] = $this->stageOptions();

			$class = $this->createClass($force, $inline);
			$view  = $this->createView($force, $inline);

			$test = $this->handleCreateTest($test, $force);

			if ($class || $view) {
				$this->linesClassAndViews($class, $view, $inline, $test);

				$this->lineWelcome();
				$this->lineBladeTag();
			}
		}

		protected function handleCreateTest($test, $force)
		{
			if ($test) {
				$testType = $this->option('pest') ? 'pest' : 'phpunit';
				$test     = $this->createTest($force, $testType);
			}

			return $test;
		}

		protected function linesClassAndViews($class, $view, $inline, $test): void
		{
			$this->line("<options=bold,reverse;fg=green> COMPONENT CREATED </> 🤙\n");
			$class && $this->line("<options=bold;fg=green>CLASS:</> {$this->parser->relativeClassPath()}");

			if (! $inline) {
				$view && $this->line("<options=bold;fg=green>VIEW:</>  {$this->parser->relativeViewPath()}");
			}

			if ($test) {
				$this->line("<options=bold;fg=green>TEST:</>  {$this->parser->relativeTestPath()}");
			}
		}

		protected function lineClassNameValid()
		{
			if (! $this->isClassNameValid($name = $this->parser->className())) {
				$this->line("<options=bold,reverse;fg=red> WHOOPS! </> 😳 \n");
				$this->line("<fg=red;options=bold>Class is invalid:</> {$name}");

				return null;
			}

			return $name;
		}

		protected function lineWelcome()
		{
			if ($this->isFirstTimeMakingAComponent() && ! app()->runningUnitTests()) {
				// @codeCoverageIgnoreStart
				$this->writeWelcomeMessage();
				// @codeCoverageIgnoreEnd
			}
		}

		protected function lineBladeTag(): void
		{
			$bladeTag = StringHelper::tagFromInputName(
				$this->package()
					 ->getName(),
				$this->parser->getInputName(),
			);

			$this->line("<options=bold;fg=green>TAG:</>  $bladeTag");
		}

		protected function lineReservedClassName(string|Stringable $name): bool
		{
			if ($this->isReservedClassName($name)) {
				$this->line("<options=bold,reverse;fg=red> WHOOPS! </> 😳 \n");
				$this->line("<fg=red;options=bold>Class is reserved:</> {$name}");

				return false;
			}

			return true;
		}

		protected function handle_package(): void
		{
			$classNamespace = $this->toPackageNamespace('Livewire');
			$viewPath       = $this->intoPackagePath('resources/views/livewire');
			$name           = $this->argument('name');
			$this->parser   = new ComponentParser(
				$classNamespace,
				$viewPath,
				$name,
				$this->option('stub'),
			);

			// important the your-package-name::livewire.klicker
			$this->parser->setPackage($this->package())
						 ->setInputName($this->argument('name'))
			;
			// change classes and namespaces for a paxsy package dir
			$this->parser->remapClasses();

			$this->parentHandle();
		}

		public function isFirstTimeMakingAComponent(): bool
		{
			$package = $this->package();
			if (! $package) {
				return parent::isFirstTimeMakingAComponent();
			}
			// todo take livewire config for namespace in paxsy config or stack config
			//$namespace = str(config('livewire.class_namespace'))->replaceFirst(app()->getNamespace(), '');
			$packagePath = $package->intoPackagePath('src/Livewire');

			return ! File::isDirectory($packagePath);
		}
	}
}
