<?php

declare(strict_types=1);

namespace think\Whoops;

use think\Service;

class WhoopsService extends Service
{
    public function register()
    {
        $this->app->bind('whoops', Whoops::class);
        $this->app->bind('think\exception\Handle', RenderExceptionWithWhoops::class);
    }
}
