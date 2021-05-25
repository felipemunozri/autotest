<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @var User
     */
    protected $user;

    protected $connectionsToTransact = [
        'public',
        'acl',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware([
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class,
        ]);
    }

    protected function login($user = null, $driver = 'web')
    {

        if(!$user) {
            $user = User::query()->first();
        } else {
            $user = User::query()->findOrFail($user);
        }
        $this->be($user, $driver);

        return $this->user = $user;
    }
}
