<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

namespace Filefabrik\Paxsy\Support\Stubs;

/**
 * Handles the configuration structure
 */
readonly class FromConfig
{
    public function __construct(public string $selectedStubs)
    {
    }

    public function getSelectedStubs(): string
    {
        return $this->selectedStubs;
    }

    public function directory(): ?string
    {
        return $this->getConfigBlock($this->directoryLocator());
    }

    private function getConfigBlock(string $block): null|string|array|bool
    {
        return config($block);
    }

    public function directoryLocator(): ?string
    {
        return $this->innerStructure('directory');
    }

    private function innerStructure(?string $segment = null): string
    {
        return rtrim('paxsy.stub_sets.'.$this->selectedStubs, '.').($segment ?
                '.'.trim($segment, '.') : '');
    }

    public function stubs(): ?array
    {
        return $this->getConfigBlock($this->stubsLocator());
    }

    public function stubsLocator(): string
    {
        return $this->innerStructure('stubs');
    }

    public function replacementMap(): array
    {
        return $this->getConfigBlock($this->replacementMapLocator()) ?? [];
    }

    public function replacementMapLocator(): string
    {
        return $this->innerStructure('replacementMap');
    }

    public function getVariablesRenderer(): array
    {
        return $this->getConfigBlock($this->variablesRendererLocator()) ?? [];
    }

    public function variablesRendererLocator(): string
    {
        return 'paxsy.VariablesRenderer';
    }
}
