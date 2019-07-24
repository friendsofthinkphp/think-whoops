<?php
declare(strict_types = 1);

namespace think\Whoops\Hander;

use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;
use think\Response;
use think\App;

use Throwable;
use Whoops\Run;
use think\Whoops\Whoops;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
class WhoopsExceptionHandle extends Handle
{
    private $whoops;

    public function __construct(App $app, Run $run)
    {
        parent::__construct($app);

        $this->whoops = new Whoops($run);
    }

    public function render($request, Throwable $e): Response
    {
        // 参数验证错误
        if ($e instanceof ValidateException) {
            return json($e->getError(), 422);
        }

        if (env('APP_DEBUG')) {
            
            // 请求异常
            $this->whoops->pushHandler(new PrettyPageHandler);

            if ($e instanceof HttpException && $request->isAjax()) {
                $this->whoops->pushHandler(new JsonResponseHandler);
            }

            $content = $this->whoops->getHandleException($e);

            return Response::create(
                $content,
                $e->getStatusCode(),
                $e->getHeaders()
            );
        }

        // 其他错误交给系统处理
        return parent::render($request, $e);
    }
}
