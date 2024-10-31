<?php
interface IRouter
{
    public function addRoute(IRoute $route, string $method, string $path, callable $callback, callable $middleware = null): void;
    public function run(string $method, string $path): void;
}
