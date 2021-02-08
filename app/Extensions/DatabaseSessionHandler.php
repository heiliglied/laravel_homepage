<?php

namespace App\Extensions;

use Illuminate\Contracts\Auth\Factory;
use Illuminate\Session\DatabaseSessionHandler as BaseDatabaseSessionHandler;

class DatabaseSessionHandler extends BaseDatabaseSessionHandler
{
    protected function addUserInformation(&$payload)
    {
        if ($this->container->bound(Factory::class)) {
            $payload['web_user_id'] = $this->webUserId();
            $payload['admin_user_id'] = $this->adminUserId();
        }

        return $this;
    }

    protected function webUserId()
    {
        $user = $this->getUser('web');
        
        return $user ? $user->id : null;
    }

    protected function adminUserId()
    {
        $user = $this->getUser('admin');
        
        return $user ? $user->id : null;
    }

    protected function getUser($guard)
    {
        return $this->container->make(Factory::class)->guard($guard)->user();
    }
}