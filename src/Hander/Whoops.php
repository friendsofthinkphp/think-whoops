<?php

declare(strict_types=1);

namespace think\Whoops\Hander;

use think\exception\Handle;
use think\Response;
use think\App;

use Throwable;
use think\Whoops\Run;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;

class Whoops extends Handle
{
    private $runner;

    public function __construct(App $app, Run $run)
    {
        parent::__construct($app);
        $this->runner = $run;
        $this->runner->register();
    }

    public function render($request, Throwable $e): Response
    {
        // Whoops 接管请求异常
        if (config('whoops.enable')) {
            $this->runner->pushHandler(new PrettyPageHandler);

            // 兼容 Cors请求
            if ($request->isAjax() || $_SERVER['HTTP_SEC_FETCH_MODE'] === 'cors') {
                $this->runner->pushHandler(new JsonResponseHandler);
            }

            return $this->runner->handleException($e);
        }

        // 其他错误交给系统处理
        return parent::render($request, $e);
    }
}
