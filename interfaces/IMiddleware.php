<?php
interface IMiddleware
{
    public function execute(array $headers, array $query, array $body): void;
}
