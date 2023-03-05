<?php

declare(strict_types=1);

namespace Tests\Ragnarok\Fenrir\Rest;

use Ragnarok\Fenrir\Parts\Invite;
use Ragnarok\Fenrir\Rest\Invite as RestInvite;

class InviteTest extends HttpHelperTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->httpItem = new RestInvite($this->http, $this->jsonMapper);
    }

    public function httpBindingsProvider(): array
    {
        return [
            'Get invite' => [
                'method' => 'get',
                'args' => ['::code::'],
                'mockOptions' => [
                    'method' => 'get',
                    'return' => (object) [],
                ],
                'validationOptions' => [
                    'returnType' => Invite::class,
                    'array' => true,
                ]
            ],
            'Delete invite' => [
                'method' => 'delete',
                'args' => ['::code::'],
                'mockOptions' => [
                    'method' => 'delete',
                    'return' => null,
                ],
                'validationOptions' => []
            ],
        ];
    }
}
