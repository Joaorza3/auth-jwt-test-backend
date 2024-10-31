<?php
interface IRoute
{
    public function getBasePath(): string;
    public function registerRoutes(IRouter $router): void;
    public function getMiddleware(): IMiddleware | null;
}
