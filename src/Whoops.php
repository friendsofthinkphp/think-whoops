<?php

namespace think\Whoops;

use think\App;
use think\facade\Env;
use think\facade\Request;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Whoops
{
    private $run;

    private $options = [
        'editor' => '',
        'title'  => '发生内部错误,请稍后再试',
    ];

    public function __construct(Run $run)
    {
        $this->run = $run;
        $this->options = array_merge($this->options, config('whoops'));
    }

    public function __call($name, $arguments)
    {
        call_user_func_array([$this->run, $name], $arguments);
    }

    public function handleException($exception)
    {
        $handlers = $this->run->getHandlers();

        foreach ($handlers as $handler) {
            if ($handler instanceof PrettyPageHandler) {
                $handler->addDataTable('ThinkPHP Application', [
                    'Version'      => App::VERSION,
                    'URI'          => Request::url(true),
                    'Request URI'  => Request::url(),
                    'Path Info'    => Request::pathinfo(),
                    'Query String' => Request::query() ?: '<none>',
                    'HTTP Method'  => Request::method(),
                    'Base URL'     => Request::baseUrl(),
                    'Scheme'       => Request::scheme(),
                    'Port'         => Request::port(),
                    'Host'         => Request::host(),
                ]);

                // 从异常堆栈跟踪中打开代码编辑器
                if ($this->options['editor']) {
                    $handler->setEditor($this->options['editor']);
                }

                // 设置标题
                $handler->setPageTitle($this->options['title']);
            }
        }

        $this->run->handleException($exception);
    }
}
