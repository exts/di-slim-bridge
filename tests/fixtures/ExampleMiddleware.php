<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;

class ExampleMiddleware
{
    private $di;

    public function __construct(ExampleMiddlewareDI $di)
    {
        $this->di = $di;
    }

    public function __invoke(RequestInterface $req, ResponseInterface $res, callable $next)
    {
        $res->getBody()->write($this->di->output('hello testing worLD'));

        return $next($req, $res);
    }
}

class ExampleMiddlewareDI
{
    public function output($data) {
        return $data;
    }
}