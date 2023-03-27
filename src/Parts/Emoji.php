<?php

declare(strict_types=1);

namespace Ragnarok\Fenrir\Parts;

class Emoji
{
    public ?string $id;
    public ?string $name;
    /**
     * @var \Ragnarok\Fenrir\Parts\Role[]
     */
    public ?array $roles;
    public ?User $user;
    public ?bool $require_colons;
    public ?bool $managed;
    public ?bool $animated;
    public ?bool $available;
}
