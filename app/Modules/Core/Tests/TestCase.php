<?php

namespace App\Modules\Core\Tests;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Models\Permission;
use App\Modules\Core\Models\Role;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\URL;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function loginRoute()
    {
        return route('login');
    }

    protected function logoutRoute()
    {
        return route('logout');
    }

    protected function passwordRequestRoute()
    {
        return route('password.request');
    }

    protected function passwordEmailRoute()
    {
        return route('password.email');
    }

    protected function passwordResetRoute($token)
    {
        return route('password.reset', $token);
    }

    protected function passwordUpdateRoute()
    {
        return route('password.update');
    }

    protected function registerRoute()
    {
        return route('register');
    }

    protected function verificationNoticeRoute()
    {
        return route('verification.notice');
    }

    protected function verificationVerifyRoute($user)
    {
        return URL::signedRoute('verification.verify', [
            'id' => $user->id,
            'hash' => sha1($user->getEmailForVerification()),
        ]);
    }

    protected function verificationResendRoute()
    {
        return route('verification.resend');
    }

    protected function assertRedirectHome($response, $user)
    {
        if ($user->is_backend) {
            $response->assertRedirect(config('core.backend_routes_prefix'));
        } else {
            $response->assertRedirect(config('core.frontend_routes_prefix'));
        }
    }

    protected function create($class, $attributes = [], $times = null)
    {
        return factory($class, $times)->create($attributes);
    }

    protected function make($class, $attributes = [], $times = null)
    {
        return factory($class, $times)->make($attributes);
    }

    protected function backendUser($permissions = [])
    {
        foreach ($permissions as $permission) {
            $this->create(Permission::class, [
                'name' => $permission,
            ]);
        }
        $permissions = Permission::pluck('id')->toArray();

        $role = $this->create(Role::class);
        $role->permissions()->sync($permissions);

        $user = $this->create(Account::class, [
            'is_enabled' => true,
            'is_backend' => true,
        ]);
        $user->roles()->attach($role->id);

        return $user;
    }
}
