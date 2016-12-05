<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;

class ExampleController
{
    public function index(RequestInterface $request, ResponseInterface $response, $args) : ResponseInterface
    {
        $response->getBody()->write("hello");

        return $response;
    }
}