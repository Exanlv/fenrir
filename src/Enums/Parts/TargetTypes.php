<?php

declare(strict_types=1);

namespace Ragnarok\Fenrir\Enums\Parts;

enum TargetTypes: int
{
    case STREAM = 1;
    case EMBEDDED_APPLICATION = 2;
}
