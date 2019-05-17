<?php

namespace Rareloop\Hatchet\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rareloop\Lumberjack\Exceptions\Handler as LumberjackHandler;
use Rareloop\Lumberjack\Facades\Config;
use Rareloop\Lumberjack\Facades\Log;
use Rareloop\Lumberjack\Http\Responses\TimberResponse;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Output\ConsoleOutput;
use Timber\Timber;
use Zend\Diactoros\Response\EmptyResponse;

class Handler extends LumberjackHandler
{
    protected $dontReport = [];

    public function report(Exception $e)
    {
        parent::report($e);
    }

    public function render(ServerRequestInterface $request, Exception $e) : ResponseInterface
    {
        (new ConsoleApplication)->renderException($e, new ConsoleOutput);

        // Not ideal :(
        return new EmptyResponse();
    }
}
