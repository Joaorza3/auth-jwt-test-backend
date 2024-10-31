<?php
abstract class BaseRoute implements IRoute
{
    private $basePath;
    private $middleware;

    public function __construct(string $basePath, IMiddleware $middleware = null)
    {
        $this->basePath = $basePath;
        $this->middleware = $middleware;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function getMiddleware(): IMiddleware | null
    {
        return $this->middleware;
    }

    public function registerRoutes(IRouter $router): void
    {
        throw new Exception('Method not implemented');
    }
}
