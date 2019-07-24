<?php
declare(strict_types = 1);

namespace think\Whoops;

use Throwable;
use Whoops\Run;
use Whoops\Handler\Handler;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;

class Whoops
{
    private $run;

    public function __construct(Run $run)
    {
        $this->run = $run;
        $this->run->register();
    }

    public function pushHandler($handler): void
    {
        if (false === $handler instanceof Handler) {
            return;
        }

        if ($handler instanceof PrettyPageHandler) {
            $handler->setPageTitle('哇哦！框架出错了！');
        }

        $this->run->pushHandler($handler);
    }

    public function getHandleException(Throwable $e): String
    {
        return $this->run->handleException($e);
    }
}
