<?php

declare(strict_types=1);

namespace think\Whoops;

use think\Service;
use think\Whoops\Hander\Whoops;

class WhoopsService extends Service
{
    public function register()
    {
        $this->app->bind('think\exception\Handle', Whoops::class);
    }
}
