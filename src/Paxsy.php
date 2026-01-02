<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */



declare(strict_types=1);

namespace Filefabrik\Paxsy;

use UnexpectedValueException;

class Paxsy
{
    // unique key app container key for the composer handler
    public static function currentStackName()
    {
        return config('paxsy.stack_name') ?? throw new UnexpectedValueException('Missing StackName');
    }
}
