<?php

namespace think\Whoops;

use think\App;
use think\Facade\Request;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Runner
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
                    'Version'         => App::VERSION,
                    'Accept Charset'  => Request::header('ACCEPT_CHARSET') ?: '<none>',
                    'HTTP Method'     => Request::method(),
                    'Path'            => Request::pathinfo(),
                    'Query String'    => Request::query() ?: '<none>',
                    'Base URL'        => Request::baseUrl(),
                    'Scheme'          => Request::scheme(),
                    'Port'            => Request::port(),
                    'Host'            => Request::host(),
                ]);

                if ($this->options['editor']) {
                    $handler->setEditor($this->options['editor']);
                }
                $handler->setPageTitle($this->options['title']);
            }
        }

        $this->run->handleException($exception);
    }
}
