<?php

declare(strict_types=1);

namespace think\Whoops;

use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\Response;
use Throwable;
use Whoops\Handler\PrettyPageHandler;

class RenderExceptionWithWhoops extends Handle
{
    public function render($request, Throwable $e): Response
    {
        // Whoops 接管请求异常
        if (config('whoops.enable') && $this->app->isDebug()) {
            if ($e instanceof HttpResponseException) {
                return $e->getResponse();
            }

            // 兼容 Cors Postman 请求
            // $request->isAjax() 判断不太正常
            if ($request->isJson() || false !== strpos($_SERVER['HTTP_USER_AGENT'], 'Postman') || (isset($_SERVER['HTTP_SEC_FETCH_MODE']) && $_SERVER['HTTP_SEC_FETCH_MODE'] === 'cors')) {
                return $this->handleAjaxException($e);
            }

            $this->app->whoops->pushHandler(new PrettyPageHandler());

            return Response::create(
                $this->app->whoops->handleException($e),
                'html',
                $e->getCode()
            );
        }

        // 其他错误交给系统处理
        return parent::render($request, $e);
    }

    /**
     * 接管Ajax异常.
     *
     * @param Throwable $e
     *
     * @return void
     */
    protected function handleAjaxException(Throwable $e)
    {
        $data = [
            'name'    => get_class($e),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'message' => $this->getMessage($e),
            'trace'   => $e->getTrace(),
            'code'    => $this->getCode($e),
            'source'  => $this->getSourceCode($e),
            'datas'   => $this->getExtendData($e),
            'tables'  => [
                'GET Data'              => $this->app->request->get(),
                'POST Data'             => $this->app->request->post(),
                'Files'                 => $this->app->request->file(),
                'Cookies'               => $this->app->request->cookie(),
                'Session'               => $this->app->session->all(),
                'Server/Request Data'   => $this->app->request->server(),
                'Environment Variables' => $this->app->request->env(),
                'ThinkPHP Constants'    => $this->getConst(),
            ],
        ];

        $response = Response::create($data, 'json');

        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            $response->header($e->getHeaders());
        }

        return $response->code($statusCode ?? 500);
    }
}
