<?php
declare(strict_types = 1);

namespace think\Whoops;

use think\Service;
use think\Whoops\Hander\WhoopsExceptionHandle;

class WhoopsService extends Service
{
    public function register()
    {
        $this->app->bind('think\exception\Handle', WhoopsExceptionHandle::class);
    }
}
