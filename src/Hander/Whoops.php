<?php

declare(strict_types=1);

namespace think\Whoops\Hander;

use think\App;
use think\exception\Handle;
use think\Response;
use think\Whoops\Runner;
use Throwable;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;

class Whoops extends Handle
{
    private $runner;

    public function __construct(App $app, Runner $runner)
    {
        parent::__construct($app);
        $this->runner = $runner;
    }

    public function render($request, Throwable $e): Response
    {
        // Whoops 接管请求异常
        if (config('whoops.enable') && $this->app->isDebug()) {
            $this->runner->pushHandler(new PrettyPageHandler());

            // 兼容 Cors请求(一些调试接口插件)
            if ($request->isAjax() || (isset($_SERVER['HTTP_SEC_FETCH_MODE']) && $_SERVER['HTTP_SEC_FETCH_MODE'] === 'cors')) {
                $this->runner->pushHandler(new JsonResponseHandler());
            }

            $this->runner->register();

            $this->runner->handleException($e);
        }

        // 其他错误交给系统处理
        return parent::render($request, $e);
    }
}
