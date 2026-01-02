<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */



declare(strict_types=1);

namespace Filefabrik\Paxsy\Tests\Support;

trait CommandReflectorTrait
{
    public function getSignature()
    {
        return $this->signature;
    }

    public function reflectOptions()
    {
        return $this->getOptions();
    }

    public function reflectArguments()
    {
        return $this->getArguments();
    }
}
