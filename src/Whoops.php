<?php
declare(strict_types = 1);

namespace think\Whoops;

use Throwable;
use Whoops\Run;
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

    public function renderException(Throwable $e): String
    {
        $this->run->pushHandler(new PrettyPageHandler());
        return $this->run->handleException($e);
    }

    public function renderJsonException(Throwable $e): String
    {
        $this->run->pushHandler(new JsonResponseHandler());
        return $this->run->handleException($e);
    }
}
