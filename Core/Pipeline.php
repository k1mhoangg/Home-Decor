<?php

namespace Core;

class Pipeline
{
    public function process(array $middlewares, callable $controller)
    {
        $handler = array_reduce(
            array_reverse($middlewares),
            function ($next, $middleware) {
                return function () use ($middleware, $next) {
                    return $middleware->handle($next);
                };
            },
            $controller
        );

        return $handler();
    }
}