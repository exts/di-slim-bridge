<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;

class ExampleControllerDI
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index(RequestInterface $request, ResponseInterface $response, $args) : ResponseInterface
    {
        $response->getBody()->write('Hi ' . $this->user->get($args['username'] ?? 'unknown'));

        return $response;
    }
}

class User
{
    public function get($username)
    {
        return $username;
    }
}