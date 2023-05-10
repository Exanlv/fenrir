<?php

declare(strict_types=1);

namespace Ragnarok\Fenrir\Component\SelectMenu;

use Ragnarok\Fenrir\Enums\Component\SelectMenuType;

class UserSelectMenu extends SelectMenu
{
    protected SelectMenuType $type = SelectMenuType::User;
}
